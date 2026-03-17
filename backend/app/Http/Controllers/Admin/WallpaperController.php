<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallpaper;
use App\Jobs\ProcessWallpaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WallpaperController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:51200', // 最大 50MB
            'title' => 'nullable|string|max:100',
            'category_id' => 'nullable|integer'
        ]);

        $file = $request->file('image');
        
        // 存储到本地 public/wallpapers/original 目录
        $path = $file->store('wallpapers/original', 'public');
        
        // 获取图像宽高
        $imageSize = getimagesize($file->getPathname());
        $width = $imageSize[0] ?? 0;
        $height = $imageSize[1] ?? 0;

        // 1. 创建壁纸主记录 (状态 0: 处理中)
        // 注意：此处 user_id 暂时写死为 1，后续对接鉴权后改为 auth()->id()
        $wallpaper = Wallpaper::create([
            'user_id' => 1, 
            'category_id' => $request->category_id,
            'title' => $request->title ?: $file->getClientOriginalName(),
            'original_url' => $path,
            'original_width' => $width,
            'original_height' => $height,
            'original_size' => $file->getSize(),
            'status' => 0 
        ]);

        // 2. 投递异步队列任务处理图片变体
        ProcessWallpaper::dispatch($wallpaper);

        return response()->json([
            'code' => 200,
            'message' => '上传成功，后台正在生成多分辨率图片...',
            'data' => [
                'wallpaper_id' => $wallpaper->id,
                'status' => 'processing'
            ]
        ]);
    }
}
