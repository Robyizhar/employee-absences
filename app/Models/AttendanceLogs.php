<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLogs extends Model
{
    use HasFactory;

    protected $table = 'attendance_logs';

    protected $fillable = [
        'company_id',
        'machine_id',
        'employee_id',
        'scan_time',
        'status', // IN/OUT/BREAK_...
        'raw_payload',
        'verification_method',
        'is_duplicate',
        'late_seconds',
        'early_seconds',
        'late_minutes',
        'early_leave_minutes',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'scan_time' => 'datetime',
    ];

    /**
     * Process an attlog callback payload from Fingerspot and store result.
     *
     * Strategy:
     *  - map status_scan -> verification_method
     *  - deduplicate if previous scan close in time (config threshold)
     *  - determine IN/OUT by alternation per-day (first -> IN; next -> OUT; next->IN; ...)
     *  - calculate late/early seconds & minutes against configured work_start/work_end
     */
    public static function processAttlogPayload(array $payload): self|null {
        $data = $payload['data'] ?? null;
        if (!$data) return null;

        $pin = $data['pin'] ?? null;
        $scanStr = $data['scan'] ?? null;
        $statusScan = $data['status_scan'] ?? null; // verification method code

        if (!$pin || !$scanStr) return null;

        $employee = \App\Models\Employee::where('employee_code', $pin)->first();
        $machine = \App\Models\Machine::where('serial_number', $payload['cloud_id'] ?? null)->first();

        // parse time with timezone
        $tz = config('attendance.timezone', config('app.timezone', 'Asia/Jakarta'));
        $scan = Carbon::parse($scanStr, $tz);

        // map verification method - sesuaikan mapping jika beda
        $verificationMethod = match($statusScan) {
            0 => 'finger',
            1 => 'face',
            2 => 'password',
            3 => 'rfid',
            default => 'other'
        };

        // duplicate detection
        $dupThreshold = config('attendance.duplicate_threshold_seconds', 30);

        $last = self::where('employee_id', $employee?->id ?? null)
            ->whereDate('scan_time', $scan->toDateString())
            ->orderBy('scan_time', 'desc')
            ->first();

        if ($last && $last->scan_time->diffInSeconds($scan) <= $dupThreshold) {
            // Save as duplicate (opsional) OR skip saving. Di sini kita simpan dengan flag is_duplicate
            return self::create([
                'company_id' => $employee?->company_id ?? null,
                'machine_id' => $machine?->id ?? null,
                'employee_id' => $employee?->id ?? null,
                'scan_time' => $scan,
                'status' => $last->status, // keep last status, atau bisa set null
                'raw_payload' => $payload,
                'verification_method' => $verificationMethod,
                'is_duplicate' => true,
            ]);
        }

        // Determine new attendance direction (IN / OUT) by alternation per day:
        if (!$last) {
            $direction = 'IN';
        } else {
            // alternate: if last was IN -> this is OUT; if last was OUT -> this is IN
            $direction = $last->status === 'IN' ? 'OUT' : 'IN';
        }

        // Work start/end for that day
        $workStart = Carbon::parse($scan->toDateString() . ' ' . config('attendance.work_start', '08:00:00'), $tz);
        $workEnd = Carbon::parse($scan->toDateString() . ' ' . config('attendance.work_end', '17:00:00'), $tz);

        $lateSeconds = null;
        $earlySeconds = null;
        $lateMinutes = null;
        $earlyMinutes = null;

        if ($direction === 'IN') {
            if ($scan->lessThanOrEqualTo($workStart->addSeconds(config('attendance.grace_seconds', 0)))) {
                $lateSeconds = 0;
                $lateMinutes = 0;
            } else {
                $lateSeconds = $scan->diffInSeconds($workStart);
                $lateMinutes = intdiv($lateSeconds, 60);
            }
        } else { // OUT
            if ($scan->greaterThanOrEqualTo($workEnd)) {
                $earlySeconds = 0;
                $earlyMinutes = 0;
            } else {
                $earlySeconds = $workEnd->diffInSeconds($scan);
                $earlyMinutes = intdiv($earlySeconds, 60);
            }
        }

        return self::create([
            'company_id' => $employee?->company_id ?? null,
            'machine_id' => $machine?->id ?? null,
            'employee_id' => $employee?->id ?? null,
            'scan_time' => $scan,
            'status' => $direction, // IN or OUT
            'raw_payload' => $payload,
            'verification_method' => $verificationMethod,
            'is_duplicate' => false,
            'late_seconds' => $lateSeconds,
            'late_minutes' => $lateMinutes,
            'early_seconds' => $earlySeconds,
            'early_leave_minutes' => $earlyMinutes,
        ]);
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
