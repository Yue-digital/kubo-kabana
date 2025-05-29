<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GallerySetting extends Model
{
    protected $fillable = [
        'image',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
