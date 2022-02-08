<?php

namespace Insyghts\Hubstaff\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Insyghts\Hubstaff\Services\AttendanceLogService;

class AttendanceLogSavingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $attendanceLogService;

    public function __construct(AttendanceLogService $attLogSrv)
    {
        $this->attendanceLogService = $attLogSrv;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // this is input data we have recieved
        // now call the relevent functions to store this data
        // use AttendanceLogService only
        $attendanceLog = $event->attendanceLog;
        $result = $this->attendanceLogService->saveAttendanceLog($attendanceLog);
     ;
    }
}
