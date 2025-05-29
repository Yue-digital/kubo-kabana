<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }
} 