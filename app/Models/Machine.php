<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;
    protected $table = 'machines';

    protected $fillable = [
        'company_id',
        'serial_number',
        'location',
        'is_active',
    ];

}
