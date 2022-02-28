<?php

namespace Insyghts\Hubstaff\Services;

use Exception;
use Insyghts\Authentication\Helpers\Helpers;
use Insyghts\Authentication\Models\SessionToken;
use Insyghts\Authentication\Models\User;
use Insyghts\Hubstaff\Models\ActivityLog;
use Insyghts\Hubstaff\Models\ActivityScreenShot;
use Insyghts\Hubstaff\Models\AttendanceLog;

class ActivityLogService
{
    function __construct(
        ActivityLog $aLog,
        ActivityScreenShot $aScreenShot,
        AttendanceLog $attendanceLog,
        AttendanceLogService $attendanceLogService,
        User $user,
        SessionToken $sessionToken,
        HubstaffServerService $serverService
    ) {
        $this->actLog = $aLog;
        $this->actScreenShot = $aScreenShot;
        $this->attendanceLog = $attendanceLog;
        $this->attendanceLogService = $attendanceLogService;
        $this->token = Helpers::get_token();
        $this->user = $user;
        $this->sessionToken = $sessionToken;
        $this->serverService = $serverService;
        $this->serverTimestamp =  $this->serverService->getTimestamp();
        $this->serverTimeString = $this->serverTimestamp['data'];  
    }

    public function listActivityLog($filter=[])
    {
        $response = [
            'success' => false,
            'data' => 'There is some error'
        ];
        try{
            $result = $this->actLog->listActivityLog($filter);
            if(count($result) > 0){
                $response['success'] = true;
                $response['data'] = $result;
            }else{
                $response['data'] = "No records found!";
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

    public function deleteActivityLog($id)
    {
        $response = [
            'success' => false,
            'data' => "There is some error"
        ];

        try{
            $result = $this->actLog->deleteActivityLog($id, $response);
            if($result){
                $response['success'] = true;
                $response['data'] = "Record Deleted Successfully!";
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

    public function saveActivityLog($data)
    {
        $response = [
            'success' => 0,
            'data' => "There is some error",
        ];
        if ( isset($data['activity_date']) && $data['activity_date'] != NULL) {
            $activity_date = gmdate('Y-m-d G:i:s', strtotime($data['activity_date']));
            $data['activity_date'] = $activity_date;
        }else{
            $data['activity_date'] = gmdate('Y-m-d G:i:s', $this->serverTimeString);
        }
        if ( isset($data['log_from_date']) && $data['log_from_date'] != NULL) {
            $log_from_date = gmdate('Y-m-d G:i:s', strtotime($data['log_from_date']));
            $data['log_from_date'] = $log_from_date;
        }else{
            $data['log_from_date'] = gmdate('Y-m-d G:i:s', $this->serverTimeString);
        }
        if ( isset($data['log_to_date']) && $data['log_to_date'] != NULL) {
            $log_to_date = gmdate('Y-m-d G:i:s', strtotime($data['log_to_date']));
            $data['log_to_date'] = $log_to_date;
        }else{
            $data['log_to_date'] = gmdate('Y-m-d G:i:s', $this->serverTimeString);
        }
        $created_at = gmdate('Y-m-d G:i:s', $this->serverTimeString);
        $updated_at = gmdate('Y-m-d G:i:s', $this->serverTimeString);

        $user_id = app('loginUser')->getUser()->id;
        $session_token_id = $this->sessionToken->getSessionToken($user_id)->id;
        $data['user_id'] = $user_id;
        $data['session_token_id'] = $session_token_id;
        $data = [
            // currently logged-in user will be here
            'user_id' => $data['user_id'],
            // currently logged-in user session will be here
            'session_token_id' => $data['session_token_id'],
            'activity_date' => $data['activity_date'],
            'log_from_date' => $data['log_from_date'],
            'log_to_date' => $data['log_to_date'],
            'note'  =>  $data['note'],
            'keyboard_track' => $data['keyboard_track'],
            'mouse_track'   => $data['mouse_track'],
            'time_type' => $data['time_type'],
            'created_by' => app('loginUser')->getUser()->id,
            'last_modified_by' => app('loginUser')->getUser()->id,
            'deleted_by' => NULL,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ];

        try {
            $activityLog = $this->actLog->saveRecord($data);
            if ($activityLog) {
                $response['success'] = 1;
                $response['data']  = $activityLog;
            }
        } catch (Exception $e) {
            $show = get_class($e) == 'Illuminate\Database\QueryException' ? false : true;
            if ($show) {
                $response['data'] = $e->getMessage();
            }
        } finally {
            return $response;
        }
    }

    public function validateActivityLogs($data, &$response)
    {
        $valid = false;
        if ($data != null || count($data) > 0) {
            $previousEntry = $this->attendanceLog->getPreviousEntry($data);
            $valid = $this->attendanceLogService->validateConsecutiveEntry($data, $previousEntry, $response);
        }
        return $valid;
    }
}
