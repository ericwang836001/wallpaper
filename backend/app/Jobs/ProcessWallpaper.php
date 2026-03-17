<?php

namespace App\Jobs;

use App\Models\Device;
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

    public $timeout = 300; // 处理多图耗时较长，增加超时时间到5分钟

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

        // --- 1. 生成预设缩略图 (type = 1) ---
        $thumbWidths = [
            ['name' => 'thumb_small', 'width' => 300], // 小图瀑布流
            ['name' => 'thumb_large', 'width' => 800], // 大图预览
        ];

        foreach ($thumbWidths as $thumb) {
            $variantImage = clone $image;
            $variantImage->scale(width: $thumb['width']);
            
            $filename = $variantsDir . '/' . $thumb['name'] . '.webp';
            $absolutePath = storage_path('app/public/' . $filename);
            
            // 缩略图统一保存为 WebP 格式，提高网络传输速度
            $variantImage->toWebp(80)->save($absolutePath);

            WallpaperVariant::create([
                'wallpaper_id' => $this->wallpaper->id,
                'type' => 1,
                'url' => $filename,
                'width' => $variantImage->width(),
                'height' => $variantImage->height(),
                'file_size' => filesize($absolutePath),
            ]);
        }

        // --- 2. 获取并处理启用的设备分辨率 (type = 3) ---
        // 去重获取所有不同的激活分辨率
        $resolutions = Device::where('is_active', true)
            ->select('screen_width', 'screen_height')
            ->groupBy('screen_width', 'screen_height')
            ->get();

        foreach ($resolutions as $res) {
            // 如果原图分辨率小于设备分辨率，跳过生成该分辨率（防止模糊拉伸）
            if ($this->wallpaper->original_width < $res->screen_width && $this->wallpaper->original_height < $res->screen_height) {
                continue;
            }

            $variantImage = clone $image;
            // 按设备宽高比例居中裁剪并缩放
            $variantImage->cover($res->screen_width, $res->screen_height);

            $filename = $variantsDir . '/device_' . $res->screen_width . 'x' . $res->screen_height . '.jpg';
            $absolutePath = storage_path('app/public/' . $filename);
            
            $variantImage->toJpeg(85)->save($absolutePath);

            WallpaperVariant::create([
                'wallpaper_id' => $this->wallpaper->id,
                'type' => 3,
                'url' => $filename,
                'width' => $res->screen_width,
                'height' => $res->screen_height,
                'file_size' => filesize($absolutePath),
            ]);
        }

        // 处理完成，更新主表状态为 1: 已发布
        $this->wallpaper->update(['status' => 1]);
    }
}
