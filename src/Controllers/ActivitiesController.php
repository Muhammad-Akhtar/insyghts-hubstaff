<?php

namespace Insyghts\Hubstaff\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Insyghts\Authentication\Middleware\myAuth;
use Insyghts\Hubstaff\Services\ActivityScreenShotService;
use Insyghts\Hubstaff\Services\ActivityLogService;

class ActivitiesController extends Controller
{
    public function __construct(ActivityLogService $aLog, 
                                ActivityScreenShotService $aScreenShot)
    {
        $this->middleware(myAuth::class);
        $this->actLogService = $aLog;
        $this->actScreenShotService = $aScreenShot;
    }

    public function listActivityLog(Request $request)
    {
        $filter = $request->all();
        $result = $this->actLogService->listActivityLog($filter);
        if($result['success']){
            return response()->json($result);
        }else{
            return response()->json(['success' => false, 'message' => $result['message']]);
        }
    }

    public function listActivityScreenshots(Request $request)
    {
        $filter = $request->all();
        $result = $this->actLogService->listActivityScreenshots($filter);
        if($result['success']){
            return response()->json($result);
        }else{
            return response()->json(['success' => false, 'message' => $result['message']]);
        }
    }

    public function storeActivityLog(Request $request)
    {
        $input = $request->all();
        $result = $this->actLogService->saveActivityLog($input);
        if($result['success']){
            if(isset($input['screen_shots'])){
                $result = $this->actScreenShotService->saveActivityScreenShot($input, $result['data']);
            }
            return response()->json(['success' => true, 'message' => $result['data']]);
        }else{
            return response()->json(['success' => false, 'message' => $result['data']]);
        }
    }

    public function deleteActivityLog($id)
    {
        $result = $this->actLogService->deleteActivityLog($id);
        if($result['success']){
            return response()->json($result);
        }else{
            return response()->json(['success' => false, 'message' => $result['message']]);
        }
    }
}
