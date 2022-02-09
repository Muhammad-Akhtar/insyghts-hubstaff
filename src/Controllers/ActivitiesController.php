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
        // Not completed yet by babar
        // $this->middleware(myAuth::class);
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
