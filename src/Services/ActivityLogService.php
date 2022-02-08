<?php

namespace Insyghts\Hubstaff\Services;

use Exception;
use Insyghts\Hubstaff\Models\ActivityLog;
use Insyghts\Hubstaff\Models\ActivityScreenShot;
use Insyghts\Hubstaff\Models\AttendanceLog;

class ActivityLogService
{
    function __construct(
        ActivityLog $aLog,
        ActivityScreenShot $aScreenShot,
        AttendanceLog $attendanceLog,
        AttendanceLogService $attendanceLogService
    ) {
        $this->actLog = $aLog;
        $this->actScreenShot = $aScreenShot;
        $this->attendanceLog = $attendanceLog;
        $this->attendanceLogService = $attendanceLogService;
    }

    public function saveActivityLog($data)
    {
        $response = [
            'success' => 0,
            'data' => "There is some error",
        ];
        if($data['activity_date'] != NULL){
            $activity_date = gmdate('Y-m-d G:i:s', strtotime($data['activity_date']));
            $data['activity_date'] = $activity_date;
        }
        if($data['log_from_date'] != NULL){
            $log_from_date = gmdate('Y-m-d G:i:s', strtotime($data['log_from_date']));
            $data['log_from_date'] = $log_from_date;
        }
        if($data['log_to_date'] != NULL){
            $log_to_date = gmdate('Y-m-d G:i:s', strtotime($data['log_to_date']));
            $data['log_to_date'] = $log_to_date;
        }
        
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
            // currently logged-in user id here
            'created_by' => $data['created_by'],
        ];

        try {
            $activityLog = $this->actLog->saveRecord($data);
            if($activityLog){
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
