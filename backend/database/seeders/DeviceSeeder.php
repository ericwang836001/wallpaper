<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            // ========== 手机 (Mobile) ==========
            // Apple
            ['brand' => 'Apple', 'name' => 'iPhone 15 Pro Max', 'type' => 'mobile', 'screen_width' => 1290, 'screen_height' => 2796, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 15 Pro', 'type' => 'mobile', 'screen_width' => 1179, 'screen_height' => 2556, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 15', 'type' => 'mobile', 'screen_width' => 1170, 'screen_height' => 2532, 'os_family' => 'iOS'],
            // Samsung
            ['brand' => 'Samsung', 'name' => 'Galaxy S24 Ultra', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3120, 'os_family' => 'Android'],
            ['brand' => 'Samsung', 'name' => 'Galaxy S24+', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3120, 'os_family' => 'Android'],
            // Huawei
            ['brand' => 'Huawei', 'name' => 'Mate 60 Pro', 'type' => 'mobile', 'screen_width' => 1212, 'screen_height' => 2616, 'os_family' => 'HarmonyOS'],
            ['brand' => 'Huawei', 'name' => 'P60 Pro', 'type' => 'mobile', 'screen_width' => 1220, 'screen_height' => 2700, 'os_family' => 'HarmonyOS'],
            // Xiaomi
            ['brand' => 'Xiaomi', 'name' => 'Xiaomi 14 Pro', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3200, 'os_family' => 'HyperOS'],
            ['brand' => 'Xiaomi', 'name' => 'Xiaomi 14', 'type' => 'mobile', 'screen_width' => 1200, 'screen_height' => 2670, 'os_family' => 'HyperOS'],
            
            // ========== 平板 (Tablet) ==========
            // Apple
            ['brand' => 'Apple', 'name' => 'iPad Pro 12.9 (M2)', 'type' => 'tablet', 'screen_width' => 2048, 'screen_height' => 2732, 'os_family' => 'iPadOS'],
            ['brand' => 'Apple', 'name' => 'iPad Air (5th Gen)', 'type' => 'tablet', 'screen_width' => 1640, 'screen_height' => 2360, 'os_family' => 'iPadOS'],
            ['brand' => 'Apple', 'name' => 'iPad mini (6th Gen)', 'type' => 'tablet', 'screen_width' => 1488, 'screen_height' => 2266, 'os_family' => 'iPadOS'],
            // Samsung
            ['brand' => 'Samsung', 'name' => 'Galaxy Tab S9 Ultra', 'type' => 'tablet', 'screen_width' => 1848, 'screen_height' => 2960, 'os_family' => 'Android'],

            // ========== 笔记本 (Laptop) ==========
            // Apple
            ['brand' => 'Apple', 'name' => 'MacBook Pro 16 (M3)', 'type' => 'laptop', 'screen_width' => 3456, 'screen_height' => 2234, 'os_family' => 'macOS'],
            ['brand' => 'Apple', 'name' => 'MacBook Pro 14 (M3)', 'type' => 'laptop', 'screen_width' => 3024, 'screen_height' => 1964, 'os_family' => 'macOS'],
            ['brand' => 'Apple', 'name' => 'MacBook Air 13 (M3)', 'type' => 'laptop', 'screen_width' => 2560, 'screen_height' => 1664, 'os_family' => 'macOS'],
            // Windows
            ['brand' => 'Dell', 'name' => 'XPS 15 (4K)', 'type' => 'laptop', 'screen_width' => 3840, 'screen_height' => 2400, 'os_family' => 'Windows'],
            ['brand' => 'Lenovo', 'name' => 'ThinkPad X1 Carbon', 'type' => 'laptop', 'screen_width' => 2880, 'screen_height' => 1800, 'os_family' => 'Windows'],

            // ========== 台式机/显示器 (Desktop) ==========
            ['brand' => 'Generic', 'name' => '8K Monitor', 'type' => 'desktop', 'screen_width' => 7680, 'screen_height' => 4320, 'os_family' => 'Any'],
            ['brand' => 'Apple', 'name' => 'Studio Display (5K)', 'type' => 'desktop', 'screen_width' => 5120, 'screen_height' => 2880, 'os_family' => 'macOS'],
            ['brand' => 'Generic', 'name' => '4K UHD Monitor', 'type' => 'desktop', 'screen_width' => 3840, 'screen_height' => 2160, 'os_family' => 'Any'],
            ['brand' => 'Generic', 'name' => 'QHD 2K Monitor', 'type' => 'desktop', 'screen_width' => 2560, 'screen_height' => 1440, 'os_family' => 'Any'],
            ['brand' => 'Generic', 'name' => 'FHD 1080p Monitor', 'type' => 'desktop', 'screen_width' => 1920, 'screen_height' => 1080, 'os_family' => 'Any'],
        ];

        $now = now();
        foreach ($devices as &$device) {
            $device['created_at'] = $now;
            $device['updated_at'] = $now;
        }

        DB::table('devices')->insertOrIgnore($devices);
    }
}
