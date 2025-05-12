<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'name',
        'contact_number',
        'email',
        'check_in',
        'check_out',
        'number_of_kubos',
        'room_type',
        'bonfire_usage',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'bonfire_usage' => 'boolean',
        'total_amount' => 'decimal:2'
    ];
}
