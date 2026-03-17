<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WallpaperVariant extends Model
{
    protected $fillable = [
        'wallpaper_id', 'type', 'url', 'width', 'height', 'file_size'
    ];

    public function wallpaper()
    {
        return $this->belongsTo(Wallpaper::class);
    }
}
