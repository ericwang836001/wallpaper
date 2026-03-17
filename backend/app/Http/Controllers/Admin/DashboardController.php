<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallpaper;
use App\Models\User;
use App\Models\Device;

class DashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'code' => 200,
            'data' => [
                'total_wallpapers' => Wallpaper::count(),
                'pending_wallpapers' => Wallpaper::where('status', 0)->count(),
                'total_users' => User::count(),
                'active_devices' => Device::where('is_active', true)->count(),
            ]
        ]);
    }
}
