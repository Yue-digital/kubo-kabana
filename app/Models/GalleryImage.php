<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    protected $fillable = [
        'room_id',
        'image_path',
        'order'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Rooms::class, 'room_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
