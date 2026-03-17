<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallpaper;
use App\Jobs\ProcessWallpaper;
use Illuminate\Http\Request;

class WallpaperController extends Controller
{
    public function index(Request $request)
    {
        $query = Wallpaper::with('category')->with(['variants' => function($q) {
            $q->where('type', 1)->select('wallpaper_id', 'url', 'width', 'height'); // 仅加载缩略图
        }]);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $wallpapers = $query->orderBy('id', 'desc')->paginate($request->get('per_page', 15));

        return response()->json(['code' => 200, 'data' => $wallpapers]);
    }

    public function show($id)
    {
        $wallpaper = Wallpaper::with(['category', 'variants'])->findOrFail($id);
        return response()->json(['code' => 200, 'data' => $wallpaper]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:51200',
            'title' => 'nullable|string|max:100',
            'category_id' => 'nullable|integer'
        ]);

        $file = $request->file('image');
        $path = $file->store('wallpapers/original', 'public');
        
        $imageSize = getimagesize($file->getPathname());

        $wallpaper = Wallpaper::create([
            'user_id' => $request->user()->id ?? 1, // 使用登录用户ID
            'category_id' => $request->category_id,
            'title' => $request->title ?: $file->getClientOriginalName(),
            'original_url' => $path,
            'original_width' => $imageSize[0] ?? 0,
            'original_height' => $imageSize[1] ?? 0,
            'original_size' => $file->getSize(),
            'status' => 0 
        ]);

        ProcessWallpaper::dispatch($wallpaper);

        return response()->json([
            'code' => 200,
            'message' => '上传成功，后台正在生成多分辨率图片...',
            'data' => ['wallpaper_id' => $wallpaper->id]
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|integer|in:1,2,3']); // 1:发布 2:下架 3:拒绝
        
        $wallpaper = Wallpaper::findOrFail($id);
        $wallpaper->update(['status' => $request->status]);

        return response()->json(['code' => 200, 'message' => '状态已更新']);
    }

    public function destroy($id)
    {
        $wallpaper = Wallpaper::findOrFail($id);
        $wallpaper->delete(); // 软删除
        return response()->json(['code' => 200, 'message' => '已删除']);
    }
}
