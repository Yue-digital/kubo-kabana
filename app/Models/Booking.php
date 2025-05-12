<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'check_in',
        'check_out',
        'status',
        'total',
        'source'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total' => 'decimal:2'
    ];
}
