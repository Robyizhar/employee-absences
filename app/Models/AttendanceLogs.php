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

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function machine() {
        return $this->belongsTo(Machine::class, 'machine_id');
    }
}
