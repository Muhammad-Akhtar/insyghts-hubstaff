<?php

namespace Insyghts\Hubstaff\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Insyghts\Hubstaff\Events\AttendanceLogCreated;
use Insyghts\Hubstaff\Models\ActivityLog;
use Insyghts\Hubstaff\Models\Attendance;
use Insyghts\Hubstaff\Models\AttendanceLog;

class AttendanceLogService
{

    function __construct(
        Attendance $attendance,
        AttendanceLog $attendanceLog,
        ActivityLog $activityLog
    ) {
        $this->attendance = $attendance;
        $this->attendanceLog = $attendanceLog;
        $this->activityLog = $activityLog;
    }

    public function saveAttendanceLog($data)
    {
        $response = [
            'success' => 0,
            'data' => 'There is some error while saving'
        ];

        $data['attendance_date'] = gmdate('Y-m-d', strtotime($data['attendance_date']));
        $data['attendance_status_date'] = gmdate('Y-m-d G:i:s', strtotime($data['attendance_status_date']));


        // $data = [
        //     'user_id' => 1,
        //     'session_token_id' => '1',
        //     'attendance_date' => gmdate('Y-m-d', strtotime('2022-02-02')),
        //     'attendance_status' => 'I',
        //     'attendance_status_date' => gmdate('Y-m-d h:i:s', strtotime('2022-02-02 6:00')),
        //     'status' => 'A',
        //     'created_by' => 1,
        //     'last_modified_by' => NULL,
        //     'deleted_by' => NULL,               
        // ];

        try {
            // check if already checkin or checkout
            // two consecutive check-ins or check-outs not allowed for a user
            $previousEntry = $this->attendanceLog->getPreviousEntry($data);
            $isvalid = $this->validateConsecutiveEntry($data, $previousEntry, $response);

            if ($isvalid) {
                $insertedRecord = $this->attendanceLog->saveRecord($data);
                if ($insertedRecord) {
                    $attendanceLog = $insertedRecord;
                    // now save activity log with type CI or CO
                    $activityData = [
                        'user_id' => $attendanceLog->user_id,
                        'session_token_id' => $attendanceLog->session_token_id,
                        'activity_date' => $attendanceLog->attendance_date,
                        'log_from_date' => NULL,
                        'log_to_date' => NULL,
                        'note'  =>  NULL,
                        'keyboard_track' => NULL,
                        'mouse_track'   => NULL,
                        'time_type' => $attendanceLog->attendance_status == 'I' ? 'CI' : 'CO',
                        'created_by' => $attendanceLog->created_by,
                        'last_modified_by' => NULL,
                        'deleted_by' => NULL,
                    ];
                    $result = $this->activityLog->saveRecord($activityData);
                    if($result){
                        $response['success'] = 1;
                        $response['data'] = $insertedRecord;
                    }
                }
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

    public function validateConsecutiveEntry($entry, $previousEntry, &$response)
    {
        // First case when no record, and two consecutive cases handled here
        $isvalid = false;
        if ($previousEntry) {
            if ($entry['attendance_status'] == 'I' && $previousEntry->attendance_status == $entry['attendance_status']) {
                $response['data'] = "You have already checked-in, Please checkout first";
            } elseif ($entry['attendance_status'] == 'O' && $previousEntry->attendance_status == $entry['attendance_status']) {
                $response['data'] = "You have already checked-out, Please check-in first";
            } else {
                $isvalid = true;
            }
        } else {
            if ((!$previousEntry || $previousEntry == null) && $entry['attendance_status'] == 'O') {
                $response['data'] = "Please check-in first!";
            } else {
                $isvalid = true;
            }
        }
        return $isvalid;
    }

    public static function getAttendanceLogs()
    {
        return "Attendanc logs returned";
    }
}
