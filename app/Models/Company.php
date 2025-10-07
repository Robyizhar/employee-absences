<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'code',
        'address',
        'is_active',
    ];

    public function users() {
        return $this->hasMany(User::class, 'company_id');
    }

    public function employees() {
        return $this->hasMany(Employee::class, 'company_id');
    }

    public function department() {
        return $this->hasMany(Department::class, 'company_id');
    }
}
