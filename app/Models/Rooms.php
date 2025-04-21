<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'gallery',
        'amenities',
        'num_guest'
    ];

    //
    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }
}
