<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {domain}';
    protected $description = 'Set the Telegram Bot Webhook URL';

    public function handle()
    {
        $domain = rtrim($this->argument('domain'), '/');
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $secretPath = env('TELEGRAM_WEBHOOK_SECRET', 'secret_path');
        
        if (!$botToken) {
            $this->error("❌ Error: TELEGRAM_BOT_TOKEN is not set in .env");
            return 1;
        }

        $webhookUrl = "{$domain}/api/webhook/telegram/{$secretPath}";
        
        $this->info("Setting Webhook to: {$webhookUrl}");

        $response = Http::post("https://telegram.wanghaibing.com/bot{$botToken}/setWebhook", [
            'url' => $webhookUrl,
        ]);

        if ($response->successful() && $response->json('ok')) {
            $this->info("✅ Webhook successfully set!");
            return 0;
        }

        $this->error("❌ Failed to set webhook:");
        $this->error(json_encode($response->json(), JSON_PRETTY_PRINT));
        return 1;
    }
}
