<?php

use Insyghts\Hubstaff\Controllers\ActivitiesController;
use Insyghts\Hubstaff\Controllers\AttendanceController;

Route::get('hubstaff', function(){
	echo 'Hello from the hubstaff package!';
});

Route::post('hubstaff/attendances/filter', [AttendanceController::class, 'index']);
Route::get('hubstaff/attendances', [AttendanceController::class, 'index']);
Route::post('hubstaff/attendance/save', [AttendanceController::class, 'store']);
Route::get('hubstaff/attendance/{id}', [AttendanceController::class, 'show']);
Route::post('hubstaff/user-attendance', [AttendanceController::class, 'getAttendanceByUserAndDate']);
Route::get('hubstaff/attendance/user/{id}', [AttendanceController::class, 'getAttendanceByUser']);
Route::get('hubstaff/attendance/date/{date}', [AttendanceController::class, 'getAttendanceByDate']);
Route::post('hubstaff/activity-log/save', [ActivitiesController::class, 'store']);