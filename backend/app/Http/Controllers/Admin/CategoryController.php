<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order', 'desc')->get();
        return response()->json(['code' => 200, 'data' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:categories',
            'slug' => 'required|string|max:50|unique:categories',
            'sort_order' => 'integer'
        ]);

        $category = Category::create($data);
        return response()->json(['code' => 200, 'message' => '创建成功', 'data' => $category]);
    }
}
