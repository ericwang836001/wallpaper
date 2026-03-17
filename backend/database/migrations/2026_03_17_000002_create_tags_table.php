<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('标签名称, 如 赛博朋克, 汽车');
            $table->timestamps();
        });

        Schema::create('wallpaper_tag', function (Blueprint $table) {
            $table->foreignId('wallpaper_id')->constrained('wallpapers')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->primary(['wallpaper_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallpaper_tag');
        Schema::dropIfExists('tags');
    }
};
