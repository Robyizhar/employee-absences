<?php

namespace App\Services;

use App\Models\AttendanceLogs;
use App\Models\AttendanceSummary;
use Carbon\Carbon;

class AttendanceService
{
    public function updateDailySummary(AttendanceLogs $log)
    {
        $dateWIB = Carbon::parse($log->scan_time)->timezone('Asia/Jakarta')->toDateString();

        $summary = AttendanceSummary::firstOrNew([
            'company_id'  => $log->company_id,
            'employee_id' => $log->employee_id,
            'date'        => $dateWIB,
        ]);

        // Tentukan check-in dan check-out
        if (!$summary->exists || !$summary->check_in_time || $log->scan_time < $summary->check_in_time) {
            $summary->check_in_time = $log->scan_time;
        }

        if (!$summary->exists || !$summary->check_out_time || $log->scan_time > $summary->check_out_time) {
            $summary->check_out_time = $log->scan_time;
        }

        // Hitung total jam kerja
        if ($summary->check_in_time && $summary->check_out_time) {
            $start = Carbon::parse($summary->check_in_time);
            $end   = Carbon::parse($summary->check_out_time);
            $summary->total_work_hours = $end->diffInMinutes($start) / 60;
        }

        // Tentukan status
        $checkInWIB  = Carbon::parse($summary->check_in_time)->timezone('Asia/Jakarta');
        $checkOutWIB = Carbon::parse($summary->check_out_time)->timezone('Asia/Jakarta');

        if ($checkInWIB->gt(Carbon::parse($dateWIB . ' 08:00:00', 'Asia/Jakarta'))) {
            $summary->status = 'LATE';
        } elseif ($checkOutWIB->lt(Carbon::parse($dateWIB . ' 17:00:00', 'Asia/Jakarta'))) {
            $summary->status = 'ABSENT'; // pulang cepat
        } else {
            $summary->status = 'NORMAL';
        }

        $summary->save();
    }
}
