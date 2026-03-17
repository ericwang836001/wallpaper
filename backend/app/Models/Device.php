<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'brand', 'name', 'type', 'screen_width', 'screen_height', 'os_family', 'is_active'
    ];
}
