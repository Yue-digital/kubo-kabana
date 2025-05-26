<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Home extends Model
{
    protected $table = 'home';

    protected $fillable = [
        'gallery_image_id'
    ];

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }
} 