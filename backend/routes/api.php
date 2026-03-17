<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\WallpaperController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// ===== 管理后台 API (Admin) =====
Route::prefix('admin')->group(function () {
    // 公开路由 (登录)
    Route::post('/auth/login', [AuthController::class, 'login']);

    // 受保护路由 (需 Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // 仪表盘数据
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        // 壁纸管理
        Route::get('/wallpapers', [WallpaperController::class, 'index']);
        Route::get('/wallpapers/{id}', [WallpaperController::class, 'show']);
        Route::post('/wallpapers/upload', [WallpaperController::class, 'upload']);
        Route::put('/wallpapers/{id}/status', [WallpaperController::class, 'updateStatus']);
        Route::delete('/wallpapers/{id}', [WallpaperController::class, 'destroy']);

        // 设备管理
        Route::get('/devices', [DeviceController::class, 'index']);
        Route::put('/devices/{device}/toggle-active', [DeviceController::class, 'toggleActive']);

        // 分类管理
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
    });
});
