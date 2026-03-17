<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\WallpaperController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Webhook\TelegramController;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// ===== Telegram Webhook =====
// 采用一个不易被猜到的随机前缀保护路由
Route::post('/webhook/telegram/' . env('TELEGRAM_WEBHOOK_SECRET', 'secret_path'), [TelegramController::class, 'handle']);

// ===== 管理后台 API (Admin) =====
Route::prefix('admin')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        Route::get('/wallpapers', [WallpaperController::class, 'index']);
        Route::get('/wallpapers/{id}', [WallpaperController::class, 'show']);
        Route::post('/wallpapers/upload', [WallpaperController::class, 'upload']);
        Route::put('/wallpapers/{id}/status', [WallpaperController::class, 'updateStatus']);
        Route::delete('/wallpapers/{id}', [WallpaperController::class, 'destroy']);

        Route::get('/devices', [DeviceController::class, 'index']);
        Route::put('/devices/{device}/toggle-active', [DeviceController::class, 'toggleActive']);

        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
    });
});
