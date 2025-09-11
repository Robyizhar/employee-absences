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
        'status',
        'raw_payload',
    ];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
