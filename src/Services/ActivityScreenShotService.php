<?php

namespace Insyghts\Hubstaff\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Insyghts\Hubstaff\Models\ActivityLog;
use Insyghts\Hubstaff\Models\ActivityScreenShot;
use ZipArchive;
use Insyghts\Hubstaff\Helpers\Helpers;

class ActivityScreenShotService
{
    function __construct(ActivityLog $aLog,
                        ActivityScreenShot $aScreenShot,
                        HubstaffServerService $serverService)
    {
        $this->aLog = $aLog;
        $this->aScreenShot = $aScreenShot;
        $this->serverService = $serverService;
        $this->serverTimestamp =  $this->serverService->getTimestamp();
        $this->serverTimeString = $this->serverTimestamp['data']; 
    }

    public function saveActivityScreenShot($data, $actLog)
    {
        $response = [
            'success' => 0,
            'data'   => 'There is some error'
        ];
        $bulk_insert = [];
        try{
            // zip file extraction
            if(! empty($data['screen_shots']) ){
                
                // echo '<pre>'; print_r($data['screen_shots']);
                // $myFile = file_get_contents();
                // $fpath = Helpers::get_public_path('screenshots' . DIRECTORY_SEPARATOR . '1644412750prof.png');
                // $fname = '1644412750prof.png';
                // $upFile = new UploadedFile($fpath, $fname);
                // echo '<pre>'; print_r($upFile->getClientOriginalName()); exit;
                
                $name = time().'.'.$data['screen_shots']->extension();
                $path = $data['screen_shots']->move(Helpers::get_public_path('files'), $name);
                $zip = new ZipArchive();
                $user_id = app('loginUser')->getUser()->id;
                $res = $zip->open($path);
                if($res == TRUE){
                    $zip->extractTo(Helpers::get_public_path('screenshots'));
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $imgName = $zip->getNameIndex($i);
                        // rename this image
                        $oldPath = Helpers::get_public_path('screenshots' . DIRECTORY_SEPARATOR . $imgName);
                        // file name
                        $imgName = time() . $imgName;
                        $newPath = Helpers::get_public_path('screenshots' . DIRECTORY_SEPARATOR . $imgName);
                        // renamed image with path
                        $renamed = rename($oldPath, $newPath);
                        if($renamed){
                            // file path
                            $imgPath = $newPath;
                            $imgObject = new UploadedFile($imgPath, $imgName);
                            $created_at = gmdate('Y-m-d G:i:s', $this->serverTimeString);
                            $updated_at = gmdate('Y-m-d G:i:s', $this->serverTimeString);
                            $row = [
                                'user_id' => $actLog->user_id,
                                'session_token_id' => $actLog->session_token_id,
                                'activity_log_id' => $actLog->id,
                                'image_path' => $imgPath,
                                'created_by' => $user_id,
                                'last_modified_by' => $user_id,
                                'deleted_by' => NULL,
                                'created_at' => $created_at,
                                'updated_at' => $updated_at
                            ]; 
                            $s3Path = 'screenshots' . DIRECTORY_SEPARATOR . $actLog->user_id . DIRECTORY_SEPARATOR . gmdate('Y-m-d', strtotime($actLog->activity_date)) . DIRECTORY_SEPARATOR . $imgName;
                            if($this->uploadToS3($s3Path, $imgObject))
                            { 
                                array_push($bulk_insert, $row);
                            }
                        }
                    } 
                    $zip->close();
                    // delete that zip file now
                    unlink($path);
                    $result = $this->aScreenShot->saveRecord($bulk_insert);
                    if($result){
                        $response['success'] = 1;
                        $response['data'] = "Successfully Inserted";
                    }
                }
            }
        }catch(Exception $e){
            $show = get_class($e) == 'Illuminate\Database\QueryException' ? false : true;
            if ($show) {
                $response['data'] = $e->getMessage();
            }
        }finally{
            return $response;
        }
    }

    public function uploadToS3($path, $photo)
    {
        try {
            $path = Storage::disk('s3')->put($path, file_get_contents($photo), 0777);
            return $path;
        } catch (\Throwable $e) {
            return 0;
        }
    }
}