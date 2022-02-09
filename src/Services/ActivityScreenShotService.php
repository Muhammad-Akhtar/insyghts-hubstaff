<?php

namespace Insyghts\Hubstaff\Services;

use Exception;
use Insyghts\Hubstaff\Models\ActivityLog;
use Insyghts\Hubstaff\Models\ActivityScreenShot;
use ZipArchive;
use Insyghts\Hubstaff\Helpers\Helper;

class ActivityScreenShotService
{
    function __construct(ActivityLog $aLog,
                        ActivityScreenShot $aScreenShot)
    {
        $this->aLog = $aLog;
        $this->aScreenShot = $aScreenShot;
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
                $name = time().'.'.$data['screen_shots']->extension();
                $path = $data['screen_shots']->move(Helper::get_public_path('files'), $name);
                $zip = new ZipArchive();
                $res = $zip->open($path);
                if($res == TRUE){
                    $zip->extractTo(Helper::get_public_path('screenshots'));
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $imgName = $zip->getNameIndex($i);
                        // rename this image
                        $oldName = Helper::get_public_path('screenshots' . DIRECTORY_SEPARATOR . $imgName);
                        $newName = Helper::get_public_path('screenshots' . DIRECTORY_SEPARATOR . time() . $imgName);
                        // renamed image with path
                        $renamed = rename($oldName, $newName);
                        if($renamed){
                            $imgPath = $newName;
                            $row = [
                                'user_id' => $actLog->user_id,
                                'session_token_id' => $actLog->session_token_id,
                                'activity_log_id' => $actLog->id,
                                'image_path' => $imgPath,
                                'created_by' => $actLog->created_by,
                            ];  
                            array_push($bulk_insert, $row);
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
}