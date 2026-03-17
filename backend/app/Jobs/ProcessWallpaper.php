<?php

namespace App\Jobs;

use App\Models\Wallpaper;
use App\Models\WallpaperVariant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessWallpaper implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120; // 增加超时时间

    protected $wallpaper;

    public function __construct(Wallpaper $wallpaper)
    {
        $this->wallpaper = $wallpaper;
    }

    public function handle(): void
    {
        $manager = new ImageManager(new Driver());
        $originalPath = storage_path('app/public/' . $this->wallpaper->original_url);

        if (!file_exists($originalPath)) {
            $this->wallpaper->update(['status' => 3]); // 3: 被拒绝/处理失败
            return;
        }

        $image = $manager->read($originalPath);
        $variantsDir = 'wallpapers/variants/' . $this->wallpaper->id;
        Storage::disk('public')->makeDirectory($variantsDir);

        $variantsToGenerate = [
            ['type' => 1, 'name' => 'thumb', 'width' => 600, 'height' => null], // 缩略图
            ['type' => 3, 'name' => 'mobile_1080x1920', 'width' => 1080, 'height' => 1920], // 手机
            ['type' => 3, 'name' => 'desktop_1920x1080', 'width' => 1920, 'height' => 1080], // 桌面
        ];

        foreach ($variantsToGenerate as $v) {
            $variantImage = clone $image;
            
            // 缩略图按比例缩放，设备壁纸使用 cover 裁剪以填满屏幕
            if ($v['type'] == 1) {
                $variantImage->scale(width: $v['width']);
            } else {
                $variantImage->cover($v['width'], $v['height']);
            }

            $filename = $variantsDir . '/' . $v['name'] . '.jpg';
            $absolutePath = storage_path('app/public/' . $filename);
            
            // 保存图片 (75质量压缩)
            $variantImage->toJpeg(75)->save($absolutePath);

            WallpaperVariant::create([
                'wallpaper_id' => $this->wallpaper->id,
                'type' => $v['type'],
                'url' => $filename,
                'width' => $variantImage->width(),
                'height' => $variantImage->height(),
                'file_size' => filesize($absolutePath),
            ]);
        }

        // 处理完成，更新主表状态为 1: 已发布
        $this->wallpaper->update(['status' => 1]);
    }
}
