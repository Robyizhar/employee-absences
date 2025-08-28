<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceSummaryTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('attendance_summary')->truncate();
        DB::table('attendance_summary')->insert([
            [
                'company_id' => 1,
                'employee_id' => 1,
                'date' => Carbon::today(),
                'check_in_time' => Carbon::today()->setTime(9, 0),
                'check_out_time' => Carbon::today()->setTime(17, 0),
                'total_work_hours' => 8.00,
                'status' => 'NORMAL',
            ],
        ]);
    }
}
