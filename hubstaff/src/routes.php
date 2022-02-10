<?php

use Insyghts\Hubstaff\Controllers\ActivitiesController;
use Insyghts\Hubstaff\Controllers\AttendanceController;

Route::get('hubstaff', function(){
	echo 'Hello from the hubstaff package!';
});

Route::post('hubstaff/attendances/filter', 'Insyghts\Hubstaff\Controllers\AttendanceController@attendances');
Route::get('hubstaff/attendances', 'Insyghts\Hubstaff\Controllers\AttendanceController@attendances');
Route::post('hubstaff/attendance/save', 'Insyghts\Hubstaff\Controllers\AttendanceController@storeAttendanceLog');
Route::get('hubstaff/attendance/{id}', 'Insyghts\Hubstaff\Controllers\AttendanceController@showAttendance');
Route::post('hubstaff/user-attendance', 'Insyghts\Hubstaff\Controllers\AttendanceController@getAttendanceByUserAndDate');
Route::get('hubstaff/attendance/user/{id}', 'Insyghts\Hubstaff\Controllers\AttendanceController@getAttendanceByUser');
Route::get('hubstaff/attendance/date/{date}', 'Insyghts\Hubstaff\Controllers\AttendanceController@getAttendanceByDate');
Route::post('hubstaff/activity-log/save', 'Insyghts\Hubstaff\Controllers\ActivitiesController@storeActivityLog');