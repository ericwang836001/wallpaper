<?php

namespace App\Jobs;

use App\Models\Wallpaper;
use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyzeWallpaperTags implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    protected $wallpaper;

    public function __construct(Wallpaper $wallpaper)
    {
        $this->wallpaper = $wallpaper;
    }

    public function handle(): void
    {
        $originalPath = storage_path('app/public/' . $this->wallpaper->original_url);

        if (!file_exists($originalPath)) {
            return;
        }

        // 调用大模型视觉 API 进行图像打标
        $tags = $this->analyzeImageWithAI($originalPath);

        if (empty($tags)) {
            Log::info("Wallpaper {$this->wallpaper->id} AI Analysis returned no tags or API not configured.");
            return;
        }

        $tagIds = [];
        foreach ($tags as $tagName) {
            $tagName = trim($tagName);
            if (empty($tagName)) continue;

            // 查找或创建标签
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }

        // 绑定标签到壁纸 (不清除原有的)
        if (!empty($tagIds)) {
            $this->wallpaper->tags()->syncWithoutDetaching($tagIds);
            Log::info("Wallpaper {$this->wallpaper->id} tagged with: " . implode(', ', $tags));
        }
    }

    /**
     * 调用大模型 Vision API 分析图像
     * @return array 解析出的标签数组，若未配置或失败则返回空数组
     */
    private function analyzeImageWithAI($imagePath): array
    {
        $apiKey = env('VISION_API_KEY');
        $apiUrl = env('VISION_API_URL', 'https://api.openai.com/v1/chat/completions');
        $model = env('VISION_API_MODEL', 'gpt-4o-mini');

        // 严格遵循主人指示：未配置时不执行任何兜底随机逻辑，直接返回空数组
        if (empty($apiKey)) {
            Log::warning("VISION_API_KEY is not configured. Auto-tagging skipped for wallpaper {$this->wallpaper->id}.");
            return [];
        }

        try {
            // 优先查找是否已生成了小尺寸缩略图 (type = 1)，以大幅度节约大模型计算资源与 Token
            $thumbVariant = $this->wallpaper->variants()->where('type', 1)->first();
            $pathToAnalyze = $thumbVariant 
                ? storage_path('app/public/' . $thumbVariant->url) 
                : $imagePath;
                
            $imageData = base64_encode(file_get_contents($pathToAnalyze));
            $mimeType = mime_content_type($pathToAnalyze);

            $response = Http::withToken($apiKey)->timeout(30)->post($apiUrl, [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => '请分析这张壁纸图片的内容，提取出 2 到 5 个核心标签词汇（如：天空, 大海, 汽车, 城市, 美女）。请只返回逗号分隔的纯中文词汇，不要包含任何其他解释文字。'],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:{$mimeType};base64,{$imageData}",
                                    'detail' => 'low' // 使用 low detail 节约 token 且足够打标签
                                ]
                            ]
                        ]
                    ]
                ],
                'max_tokens' => 50
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                if ($content) {
                    // 清理可能存在的句号并拆分
                    $content = str_replace(['，', '。', '.'], [',', '', ''], $content);
                    return explode(',', $content);
                }
            } else {
                Log::error('Vision API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Vision API Exception: ' . $e->getMessage());
        }

        return [];
    }
}
