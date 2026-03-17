<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        // 按壁纸关联数量倒序，分页
        $tags = Tag::withCount('wallpapers')
            ->orderBy('wallpapers_count', 'desc')
            ->paginate($request->get('per_page', 50));
            
        return response()->json(['code' => 200, 'data' => $tags]);
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return response()->json(['code' => 200, 'message' => '已删除']);
    }
}
