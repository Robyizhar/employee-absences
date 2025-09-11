<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'company_id',
        'employee_code',
        'name',
        'department',
        'position',
        'is_active',
    ];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
