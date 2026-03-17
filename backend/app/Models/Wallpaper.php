<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallpaper extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'description', 
        'original_url', 'original_width', 'original_height', 'original_size', 
        'status', 'views_count', 'likes_count', 'downloads_count'
    ];

    public function variants()
    {
        return $this->hasMany(WallpaperVariant::class);
    }
}
