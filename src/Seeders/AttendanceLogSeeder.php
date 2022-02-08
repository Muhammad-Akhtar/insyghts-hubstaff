<?php

namespace Insyghts\Hubstaff\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AttendanceLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'user_id' => 1,
            'session_token_id' => '1',
            'attendance_date' => date('Y-m-d', strtotime('2022-02-01')),
            'attendance_status' => 'I',
            'attendance_status_date' => date('Y-m-d h:i:s', strtotime('2022-02-01 10:00')),
            'status' => 'A',
            'created_by' => 1,
            'last_modified_by' => NULL,
            'deleted_by' => NULL,            
        ];

        $inserted = DB::table('attendance_logs')->insert(
            $data
        );


    }
}
