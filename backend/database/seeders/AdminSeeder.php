<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 创建管理员账号
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@wallpaper.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 1
            ]
        );

        // 创建默认壁纸分类
        $categories = [
            ['name' => '风景自然', 'slug' => 'nature', 'sort_order' => 100],
            ['name' => '极简艺术', 'slug' => 'minimalist', 'sort_order' => 90],
            ['name' => '赛博朋克', 'slug' => 'cyberpunk', 'sort_order' => 80],
            ['name' => '动漫二次元', 'slug' => 'anime', 'sort_order' => 70],
            ['name' => '宇宙星空', 'slug' => 'space', 'sort_order' => 60],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
