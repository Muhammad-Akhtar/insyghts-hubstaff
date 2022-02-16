<?php

namespace Insyghts\Hubstaff\Controllers;

use Illuminate\Http\Request;
use Insyghts\Authentication\Middleware\myAuth;
use Insyghts\Common\Controllers\CommonController;
use Insyghts\Hubstaff\Services\ActivityScreenShotService;
use Insyghts\Hubstaff\Services\ActivityLogService;

class ActivitiesController extends CommonController
{
    public function __construct(ActivityLogService $aLog, 
                                ActivityScreenShotService $aScreenShot)
    {
        $this->middleware(myAuth::class);
        $this->actLogService = $aLog;
        $this->actScreenShotService = $aScreenShot;
    }

    public function storeActivityLog(Request $request)
    {
        // Data with a zip file.
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
}
