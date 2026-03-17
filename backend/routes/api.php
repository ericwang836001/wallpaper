<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WallpaperController;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// 管理后台接口分组
Route::prefix('admin')->group(function () {
    // 壁纸上传接口 (后续需要加入 admin 中间件鉴权)
    Route::post('/wallpapers/upload', [WallpaperController::class, 'upload']);
});
