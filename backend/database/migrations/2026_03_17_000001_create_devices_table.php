<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 50)->comment('品牌, 如 Apple, Samsung, Huawei');
            $table->string('name', 100)->comment('设备名称, 如 iPhone 15 Pro Max');
            $table->enum('type', ['mobile', 'tablet', 'laptop', 'desktop'])->comment('设备类型');
            $table->integer('screen_width')->unsigned()->comment('屏幕物理分辨率宽度');
            $table->integer('screen_height')->unsigned()->comment('屏幕物理分辨率高度');
            $table->string('os_family', 50)->comment('操作系统家族, 如 iOS, Android, macOS, Windows');
            $table->boolean('is_active')->default(true)->comment('是否启用该设备的分辨率生成');
            $table->timestamps();
            
            // 确保同一品牌下的同一设备名唯一
            $table->unique(['brand', 'name']);
            // 建立宽高索引，方便后续在队列中做去重（GROUP BY 宽高）
            $table->index(['screen_width', 'screen_height']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
