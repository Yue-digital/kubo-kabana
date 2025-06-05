<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = 'about_setting';

    protected $fillable = [
        'description',
        'policy_file',
    ];
}
