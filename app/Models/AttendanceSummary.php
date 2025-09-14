<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceSummary extends Model
{
    use HasFactory;

    protected $table = 'attendance_summary';

    protected $fillable = [
        'company_id',
        'employee_id',
        'date',
        'check_in_time',
        'check_out_time',
        'total_work_hours',
        'status',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
