<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            // ========== Apple 手机 (iOS) ==========
            ['brand' => 'Apple', 'name' => 'iPhone 15 Pro Max', 'type' => 'mobile', 'screen_width' => 1290, 'screen_height' => 2796, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 15 Pro', 'type' => 'mobile', 'screen_width' => 1179, 'screen_height' => 2556, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 15 Plus', 'type' => 'mobile', 'screen_width' => 1284, 'screen_height' => 2778, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 15', 'type' => 'mobile', 'screen_width' => 1170, 'screen_height' => 2532, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 14 Pro Max', 'type' => 'mobile', 'screen_width' => 1290, 'screen_height' => 2796, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 14 Pro', 'type' => 'mobile', 'screen_width' => 1179, 'screen_height' => 2556, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 14 Plus', 'type' => 'mobile', 'screen_width' => 1284, 'screen_height' => 2778, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 14', 'type' => 'mobile', 'screen_width' => 1170, 'screen_height' => 2532, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 13 Pro Max', 'type' => 'mobile', 'screen_width' => 1284, 'screen_height' => 2778, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 13 Pro', 'type' => 'mobile', 'screen_width' => 1170, 'screen_height' => 2532, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone 13 mini', 'type' => 'mobile', 'screen_width' => 1080, 'screen_height' => 2340, 'os_family' => 'iOS'],
            ['brand' => 'Apple', 'name' => 'iPhone SE (3rd Gen)', 'type' => 'mobile', 'screen_width' => 750, 'screen_height' => 1334, 'os_family' => 'iOS'],

            // ========== Samsung 手机 (Android) ==========
            ['brand' => 'Samsung', 'name' => 'Galaxy S24 Ultra', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3120, 'os_family' => 'Android'],
            ['brand' => 'Samsung', 'name' => 'Galaxy S24+', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3120, 'os_family' => 'Android'],
            ['brand' => 'Samsung', 'name' => 'Galaxy S24', 'type' => 'mobile', 'screen_width' => 1080, 'screen_height' => 2340, 'os_family' => 'Android'],
            ['brand' => 'Samsung', 'name' => 'Galaxy Z Fold 5 (Inner)', 'type' => 'tablet', 'screen_width' => 1812, 'screen_height' => 2176, 'os_family' => 'Android'],
            ['brand' => 'Samsung', 'name' => 'Galaxy Z Fold 5 (Cover)', 'type' => 'mobile', 'screen_width' => 904, 'screen_height' => 2316, 'os_family' => 'Android'],
            ['brand' => 'Samsung', 'name' => 'Galaxy Z Flip 5 (Inner)', 'type' => 'mobile', 'screen_width' => 1080, 'screen_height' => 2640, 'os_family' => 'Android'],
            ['brand' => 'Samsung', 'name' => 'Galaxy S23 Ultra', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3088, 'os_family' => 'Android'],
            ['brand' => 'Samsung', 'name' => 'Galaxy A54', 'type' => 'mobile', 'screen_width' => 1080, 'screen_height' => 2340, 'os_family' => 'Android'],

            // ========== Huawei 手机 (HarmonyOS) ==========
            ['brand' => 'Huawei', 'name' => 'Mate 60 Pro+', 'type' => 'mobile', 'screen_width' => 1260, 'screen_height' => 2720, 'os_family' => 'HarmonyOS'],
            ['brand' => 'Huawei', 'name' => 'Mate 60 Pro', 'type' => 'mobile', 'screen_width' => 1212, 'screen_height' => 2616, 'os_family' => 'HarmonyOS'],
            ['brand' => 'Huawei', 'name' => 'Mate X5 (Inner)', 'type' => 'tablet', 'screen_width' => 2224, 'screen_height' => 2496, 'os_family' => 'HarmonyOS'],
            ['brand' => 'Huawei', 'name' => 'Mate X5 (Cover)', 'type' => 'mobile', 'screen_width' => 1004, 'screen_height' => 2504, 'os_family' => 'HarmonyOS'],
            ['brand' => 'Huawei', 'name' => 'P60 Art', 'type' => 'mobile', 'screen_width' => 1220, 'screen_height' => 2700, 'os_family' => 'HarmonyOS'],
            ['brand' => 'Huawei', 'name' => 'P60 Pro', 'type' => 'mobile', 'screen_width' => 1220, 'screen_height' => 2700, 'os_family' => 'HarmonyOS'],
            ['brand' => 'Huawei', 'name' => 'Nova 11 Pro', 'type' => 'mobile', 'screen_width' => 1200, 'screen_height' => 2652, 'os_family' => 'HarmonyOS'],

            // ========== Xiaomi 手机 (HyperOS/Android) ==========
            ['brand' => 'Xiaomi', 'name' => 'Xiaomi 14 Ultra', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3200, 'os_family' => 'HyperOS'],
            ['brand' => 'Xiaomi', 'name' => 'Xiaomi 14 Pro', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3200, 'os_family' => 'HyperOS'],
            ['brand' => 'Xiaomi', 'name' => 'Xiaomi 14', 'type' => 'mobile', 'screen_width' => 1200, 'screen_height' => 2670, 'os_family' => 'HyperOS'],
            ['brand' => 'Xiaomi', 'name' => 'Xiaomi MIX Fold 3 (Inner)', 'type' => 'tablet', 'screen_width' => 1916, 'screen_height' => 2160, 'os_family' => 'HyperOS'],
            ['brand' => 'Redmi', 'name' => 'Redmi K70 Pro', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3200, 'os_family' => 'HyperOS'],
            ['brand' => 'Redmi', 'name' => 'Redmi Note 13 Pro+', 'type' => 'mobile', 'screen_width' => 1220, 'screen_height' => 2712, 'os_family' => 'HyperOS'],

            // ========== 其他国产品牌 (Android) ==========
            ['brand' => 'OPPO', 'name' => 'Find X7 Ultra', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3168, 'os_family' => 'Android'],
            ['brand' => 'OPPO', 'name' => 'Find N3 (Inner)', 'type' => 'tablet', 'screen_width' => 2268, 'screen_height' => 2440, 'os_family' => 'Android'],
            ['brand' => 'vivo', 'name' => 'X100 Pro', 'type' => 'mobile', 'screen_width' => 1260, 'screen_height' => 2800, 'os_family' => 'Android'],
            ['brand' => 'vivo', 'name' => 'iQOO 12 Pro', 'type' => 'mobile', 'screen_width' => 1260, 'screen_height' => 2800, 'os_family' => 'Android'],
            ['brand' => 'Honor', 'name' => 'Magic6 Pro', 'type' => 'mobile', 'screen_width' => 1280, 'screen_height' => 2800, 'os_family' => 'Android'],
            ['brand' => 'OnePlus', 'name' => '12', 'type' => 'mobile', 'screen_width' => 1440, 'screen_height' => 3168, 'os_family' => 'Android'],
            ['brand' => 'Google', 'name' => 'Pixel 8 Pro', 'type' => 'mobile', 'screen_width' => 1344, 'screen_height' => 2992, 'os_family' => 'Android'],
            ['brand' => 'Google', 'name' => 'Pixel 8', 'type' => 'mobile', 'screen_width' => 1080, 'screen_height' => 2400, 'os_family' => 'Android'],

            // ========== Apple 平板 (iPadOS) ==========
            ['brand' => 'Apple', 'name' => 'iPad Pro 12.9 (M2)', 'type' => 'tablet', 'screen_width' => 2048, 'screen_height' => 2732, 'os_family' => 'iPadOS'],
            ['brand' => 'Apple', 'name' => 'iPad Pro 11 (M2)', 'type' => 'tablet', 'screen_width' => 1668, 'screen_height' => 2388, 'os_family' => 'iPadOS'],
            ['brand' => 'Apple', 'name' => 'iPad Air (5th Gen)', 'type' => 'tablet', 'screen_width' => 1640, 'screen_height' => 2360, 'os_family' => 'iPadOS'],
            ['brand' => 'Apple', 'name' => 'iPad (10th Gen)', 'type' => 'tablet', 'screen_width' => 1640, 'screen_height' => 2360, 'os_family' => 'iPadOS'],
            ['brand' => 'Apple', 'name' => 'iPad mini (6th Gen)', 'type' => 'tablet', 'screen_width' => 1488, 'screen_height' => 2266, 'os_family' => 'iPadOS'],

            // ========== Android 平板 ==========
            ['brand' => 'Samsung', 'name' => 'Galaxy Tab S9 Ultra', 'type' => 'tablet', 'screen_width' => 1848, 'screen_height' => 2960, 'os_family' => 'Android'],
            ['brand' => 'Samsung', 'name' => 'Galaxy Tab S9+', 'type' => 'tablet', 'screen_width' => 1752, 'screen_height' => 2800, 'os_family' => 'Android'],
            ['brand' => 'Xiaomi', 'name' => 'Xiaomi Pad 6 Max 14', 'type' => 'tablet', 'screen_width' => 1800, 'screen_height' => 2880, 'os_family' => 'HyperOS'],
            ['brand' => 'Huawei', 'name' => 'MatePad Pro 13.2', 'type' => 'tablet', 'screen_width' => 1920, 'screen_height' => 2880, 'os_family' => 'HarmonyOS'],
            ['brand' => 'Lenovo', 'name' => 'Legion Y900', 'type' => 'tablet', 'screen_width' => 1876, 'screen_height' => 3000, 'os_family' => 'Android'],

            // ========== Apple 笔记本/桌面 (macOS) ==========
            ['brand' => 'Apple', 'name' => 'MacBook Pro 16 (M3)', 'type' => 'laptop', 'screen_width' => 3456, 'screen_height' => 2234, 'os_family' => 'macOS'],
            ['brand' => 'Apple', 'name' => 'MacBook Pro 14 (M3)', 'type' => 'laptop', 'screen_width' => 3024, 'screen_height' => 1964, 'os_family' => 'macOS'],
            ['brand' => 'Apple', 'name' => 'MacBook Air 15 (M3)', 'type' => 'laptop', 'screen_width' => 2880, 'screen_height' => 1864, 'os_family' => 'macOS'],
            ['brand' => 'Apple', 'name' => 'MacBook Air 13 (M3)', 'type' => 'laptop', 'screen_width' => 2560, 'screen_height' => 1664, 'os_family' => 'macOS'],
            ['brand' => 'Apple', 'name' => 'iMac 24 (M3)', 'type' => 'desktop', 'screen_width' => 4480, 'screen_height' => 2520, 'os_family' => 'macOS'],
            ['brand' => 'Apple', 'name' => 'Pro Display XDR (6K)', 'type' => 'desktop', 'screen_width' => 6016, 'screen_height' => 3384, 'os_family' => 'macOS'],
            ['brand' => 'Apple', 'name' => 'Studio Display (5K)', 'type' => 'desktop', 'screen_width' => 5120, 'screen_height' => 2880, 'os_family' => 'macOS'],

            // ========== Windows 笔记本 (Windows) ==========
            ['brand' => 'Dell', 'name' => 'XPS 15 (4K)', 'type' => 'laptop', 'screen_width' => 3840, 'screen_height' => 2400, 'os_family' => 'Windows'],
            ['brand' => 'Dell', 'name' => 'XPS 13 (OLED)', 'type' => 'laptop', 'screen_width' => 3456, 'screen_height' => 2160, 'os_family' => 'Windows'],
            ['brand' => 'Lenovo', 'name' => 'ThinkPad X1 Carbon', 'type' => 'laptop', 'screen_width' => 2880, 'screen_height' => 1800, 'os_family' => 'Windows'],
            ['brand' => 'ASUS', 'name' => 'ROG Zephyrus G14', 'type' => 'laptop', 'screen_width' => 2560, 'screen_height' => 1600, 'os_family' => 'Windows'],
            ['brand' => 'Razer', 'name' => 'Blade 16 (4K mini-LED)', 'type' => 'laptop', 'screen_width' => 3840, 'screen_height' => 2400, 'os_family' => 'Windows'],
            ['brand' => 'Microsoft', 'name' => 'Surface Laptop Studio 2', 'type' => 'laptop', 'screen_width' => 2400, 'screen_height' => 1600, 'os_family' => 'Windows'],
            ['brand' => 'Microsoft', 'name' => 'Surface Pro 9', 'type' => 'tablet', 'screen_width' => 2880, 'screen_height' => 1920, 'os_family' => 'Windows'],

            // ========== 通用桌面显示器 (Desktop) ==========
            ['brand' => 'Generic', 'name' => '8K UHD Monitor', 'type' => 'desktop', 'screen_width' => 7680, 'screen_height' => 4320, 'os_family' => 'Any'],
            ['brand' => 'Generic', 'name' => '5K Ultrawide (21:9)', 'type' => 'desktop', 'screen_width' => 5120, 'screen_height' => 2160, 'os_family' => 'Any'],
            ['brand' => 'Generic', 'name' => '4K UHD Monitor', 'type' => 'desktop', 'screen_width' => 3840, 'screen_height' => 2160, 'os_family' => 'Any'],
            ['brand' => 'Generic', 'name' => 'WQHD Ultrawide (21:9)', 'type' => 'desktop', 'screen_width' => 3440, 'screen_height' => 1440, 'os_family' => 'Any'],
            ['brand' => 'Generic', 'name' => 'QHD 2K Monitor', 'type' => 'desktop', 'screen_width' => 2560, 'screen_height' => 1440, 'os_family' => 'Any'],
            ['brand' => 'Generic', 'name' => 'WUXGA (21:9)', 'type' => 'desktop', 'screen_width' => 2560, 'screen_height' => 1080, 'os_family' => 'Any'],
            ['brand' => 'Generic', 'name' => 'FHD 1080p Monitor', 'type' => 'desktop', 'screen_width' => 1920, 'screen_height' => 1080, 'os_family' => 'Any'],
            ['brand' => 'Generic', 'name' => 'HD 720p Monitor', 'type' => 'desktop', 'screen_width' => 1280, 'screen_height' => 720, 'os_family' => 'Any'],
        ];

        $now = now();
        foreach ($devices as &$device) {
            $device['created_at'] = $now;
            $device['updated_at'] = $now;
        }

        // 清空表数据后重新插入，防止唯一索引冲突
        DB::table('devices')->truncate();
        DB::table('devices')->insert($devices);
    }
}
