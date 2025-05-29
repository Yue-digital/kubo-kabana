<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rooms extends Model
{
    protected $fillable = [
        'name',
        'description',
        'lean_weekday_price',
        'lean_weekend_price',
        'peak_weekday_price',
        'peak_weekend_price',
        'image',
        'gallery',
        'amenities',
        'num_guest',
        'peak_season_start',
        'peak_season_end',
        'lean_season_start',
        'lean_season_end',
    ];

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class, 'room_id')->orderBy('order');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    //
    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }
}
