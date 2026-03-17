#!/bin/bash
echo "🚀 开始构建并启动 Docker 容器..."
docker compose build --no-cache
docker compose up -d

echo "📦 正在容器内使用 Composer 初始化 Laravel 11 项目..."
# 等待 PHP 容器启动
sleep 3
# 如果 backend 目录为空，则在容器内安装 Laravel
if [ ! -f "./backend/artisan" ]; then
    docker compose exec -T php composer create-project --prefer-dist laravel/laravel temp_backend
    mv ./backend/temp_backend/* ./backend/temp_backend/.* ./backend/ 2>/dev/null || true
    rm -rf ./backend/temp_backend
    
    echo "🔧 开启 Laravel 11 API 模式..."
    docker compose exec -T php php artisan install:api
    
    echo "📂 设置目录权限..."
    docker compose exec -T php chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
fi

echo "✅ 初始化完成！后端 API 服务已运行在 http://localhost:8080"
