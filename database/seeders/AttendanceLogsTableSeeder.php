<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceLogsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('attendance_logs')->truncate();
        DB::table('attendance_logs')->insert([
            [
                'company_id' => 1,
                'machine_id' => 1,
                'employee_id' => 1,
                'scan_time' => Carbon::now()->subHours(8),
                'status' => 'IN',
                'raw_payload' => json_encode(['device' => 'SN-001', 'mode' => 'fingerprint']),
            ],
            [
                'company_id' => 1,
                'machine_id' => 1,
                'employee_id' => 1,
                'scan_time' => Carbon::now(),
                'status' => 'OUT',
                'raw_payload' => json_encode(['device' => 'SN-001', 'mode' => 'fingerprint']),
            ],
        ]);
    }
}
