<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. 用户设备表 (user_devices)
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_type', 20)->comment('mobile, tablet, desktop');
            $table->string('device_name', 100)->nullable()->comment('设备名称标识');
            $table->integer('screen_width')->unsigned()->comment('屏幕物理分辨率宽度');
            $table->integer('screen_height')->unsigned()->comment('屏幕物理分辨率高度');
            $table->string('os_info', 100)->nullable()->comment('操作系统信息');
            $table->timestamp('last_active_at')->nullable()->comment('最后活跃时间');
            $table->timestamps();
            
            // 复合索引加速查询该用户在此设备宽高的命中率
            $table->index(['user_id', 'screen_width', 'screen_height']);
        });

        // 2. 分类表 (categories)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique()->comment('URL友好的别名');
            $table->integer('sort_order')->default(0)->comment('排序权重，越大越靠前');
            $table->timestamps();
        });

        // 3. 壁纸主表 (wallpapers)
        Schema::create('wallpapers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('title', 100)->nullable()->comment('壁纸标题');
            $table->string('description', 255)->nullable()->comment('壁纸描述');
            $table->string('original_url', 255)->comment('原图在OSS的路径');
            $table->integer('original_width')->unsigned()->comment('原始宽度');
            $table->integer('original_height')->unsigned()->comment('原始高度');
            $table->bigInteger('original_size')->unsigned()->comment('原始文件大小(Bytes)');
            
            // 0:处理中, 1:已发布, 2:已下架, 3:被拒绝
            $table->tinyInteger('status')->default(0)->index();
            
            // 统计数据
            $table->integer('views_count')->unsigned()->default(0);
            $table->integer('likes_count')->unsigned()->default(0);
            $table->integer('downloads_count')->unsigned()->default(0);
            
            $table->timestamps();
            $table->softDeletes(); // 软删除，防止数据误删
        });

        // 4. 壁纸变体子表 (wallpaper_variants)
        Schema::create('wallpaper_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallpaper_id')->constrained('wallpapers')->onDelete('cascade');
            
            // 1: 缩略图 (Thumbnail)
            // 2: 效果图 (Mockup)
            // 3: 适配壁纸 (Device Wallpaper)
            $table->tinyInteger('type')->index();
            
            $table->string('url', 255)->comment('该尺寸图片的OSS路径');
            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();
            $table->integer('file_size')->unsigned()->comment('变体文件大小(Bytes)');
            
            $table->timestamps();
            
            // 查询优化：通过壁纸ID和类型，或者直接查宽高等过滤
            $table->index(['wallpaper_id', 'type']);
            $table->index(['width', 'height']);
        });

        // 5. 收藏点赞关联表 (favorites)
        Schema::create('favorites', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wallpaper_id')->constrained('wallpapers')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            
            // 复合主键防止重复点赞/收藏
            $table->primary(['user_id', 'wallpaper_id']);
        });
        
        // 修改默认的 users 表增加相关字段
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('password')->comment('头像 OSS 链接');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->default('user')->after('avatar')->comment('admin 或 user');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->tinyInteger('status')->default(1)->after('role')->comment('1:正常, 0:封禁');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('wallpaper_variants');
        Schema::dropIfExists('wallpapers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('user_devices');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'role', 'status']);
        });
    }
};
