<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Wallpaper;
use App\Models\User;
use App\Jobs\ProcessWallpaper;

class TelegramController extends Controller
{
    private $botToken;
    private $allowedUsers;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
        // 允许发图发布的 Telegram User ID 列表，多个用逗号分隔
        $allowed = env('TELEGRAM_ALLOWED_USERS', '');
        $this->allowedUsers = array_filter(explode(',', $allowed));
    }

    public function handle(Request $request)
    {
        $data = $request->all();
        Log::info('Received Telegram Webhook:', $data);

        // 基本校验：是否包含 message 及其内容
        if (!isset($data['message'])) {
            return response()->json(['status' => 'ignored']);
        }

        $message = $data['message'];
        $chatId = $message['chat']['id'] ?? null;
        $userId = $message['from']['id'] ?? null;

        // 1. 权限校验
        if (!$userId || empty($this->allowedUsers) || !in_array((string)$userId, $this->allowedUsers)) {
            Log::warning("Unauthorized Telegram User ID: {$userId}");
            if ($chatId) {
                $this->sendMessage($chatId, "⚠️ 抱歉，您没有发布壁纸的权限。您的 User ID 是: {$userId}");
            }
            return response()->json(['status' => 'unauthorized']);
        }

        // 2. 检查是否包含图片 (photo) 或无损文件 (document)
        $fileId = null;
        $fileName = 'telegram_upload_' . time();
        $isDocument = false;

        // 优先处理以文件形式发送的无损大图
        if (isset($message['document']) && str_starts_with($message['document']['mime_type'] ?? '', 'image/')) {
            $fileId = $message['document']['file_id'];
            $fileName = $message['document']['file_name'] ?? $fileName;
            $isDocument = true;
        } 
        // 处理作为照片发送的压缩图（取数组最后一张，即最高清晰度）
        elseif (isset($message['photo'])) {
            $photoArray = $message['photo'];
            $bestPhoto = end($photoArray);
            $fileId = $bestPhoto['file_id'];
        }

        if (!$fileId) {
            // 不是图片，忽略但不报错
            return response()->json(['status' => 'ignored, not an image']);
        }

        // 3. 提取图片可能包含的说明文字作为标题 (Caption)
        $title = $message['caption'] ?? $fileName;

        // 4. 调用 Telegram API 获取文件下载链接
        $fileInfoResponse = Http::get("https://api.telegram.org/bot{$this->botToken}/getFile", [
            'file_id' => $fileId
        ]);

        if (!$fileInfoResponse->successful() || !$fileInfoResponse->json('ok')) {
            Log::error('Failed to get Telegram file info', $fileInfoResponse->json());
            $this->sendMessage($chatId, "❌ 获取图片下载地址失败。");
            return response()->json(['status' => 'error_getting_file']);
        }

        $filePath = $fileInfoResponse->json('result.file_path');
        $fileUrl = "https://api.telegram.org/file/bot{$this->botToken}/{$filePath}";

        // 5. 下载图片到本地 storage/app/public/wallpapers/original
        $this->sendMessage($chatId, "📥 已收到高清大图，正在极速下载原图到服务器...");
        
        $imageContent = Http::timeout(60)->get($fileUrl)->body();
        $extension = pathinfo($filePath, PATHINFO_EXTENSION) ?: 'jpg';
        $localFileName = time() . '_' . uniqid() . '.' . $extension;
        $localPath = 'wallpapers/original/' . $localFileName;

        // 保存文件
        \Illuminate\Support\Facades\Storage::disk('public')->put($localPath, $imageContent);

        // 获取原图信息
        $absolutePath = storage_path('app/public/' . $localPath);
        $imageSizeInfo = getimagesize($absolutePath);
        $width = $imageSizeInfo[0] ?? 0;
        $height = $imageSizeInfo[1] ?? 0;
        $fileSize = filesize($absolutePath);

        // 如果是超级压缩的低分辨率图片，给出警告，但仍继续处理
        if (!$isDocument && ($width < 1000 || $height < 1000)) {
            $this->sendMessage($chatId, "⚠️ 检测到该图片分辨率较低 ({$width}x{$height})。建议您在 Telegram 中勾选「原图发送」 (Send as File) 获取最高画质！");
        }

        // 6. 查找或创建关联的后台管理员账号
        // 假设超级管理员 ID 是 1
        $adminUser = User::where('role', 'admin')->first();
        $adminUserId = $adminUser ? $adminUser->id : 1;

        // 7. 写入数据库
        $wallpaper = Wallpaper::create([
            'user_id' => $adminUserId,
            'category_id' => null, // 默认不分类
            'title' => substr($title, 0, 100),
            'original_url' => $localPath,
            'original_width' => $width,
            'original_height' => $height,
            'original_size' => $fileSize,
            'status' => 0 // 处理中
        ]);

        // 8. 触发异步裁剪队列
        ProcessWallpaper::dispatch($wallpaper);

        // 9. 回复确认消息
        $sizeMB = round($fileSize / 1024 / 1024, 2);
        $replyText = "✅ **壁纸接收成功！**\n\n"
                   . "🆔 ID: `{$wallpaper->id}`\n"
                   . "📌 标题: `{$wallpaper->title}`\n"
                   . "📐 尺寸: `{$width} x {$height}`\n"
                   . "📦 大小: `{$sizeMB} MB`\n\n"
                   . "🚀 后台极速多设备适配裁剪任务已启动...";
        
        $this->sendMessage($chatId, $replyText);

        return response()->json(['status' => 'ok', 'wallpaper_id' => $wallpaper->id]);
    }

    private function sendMessage($chatId, $text)
    {
        Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown'
        ]);
    }
}
