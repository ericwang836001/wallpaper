<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    public function heartbeat()
    {
        $status = 'healthy';
        
        // 1. 检查数据库连接状态
        $dbStatus = 'ok';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'error';
            $status = 'degraded';
        }

        // 2. 检查异步队列状态 (Laravel 默认使用 database 驱动)
        $queueStatus = [
            'pending_jobs' => 0,
            'failed_jobs' => 0,
        ];
        
        try {
            if (DB::getSchemaBuilder()->hasTable('jobs')) {
                $queueStatus['pending_jobs'] = DB::table('jobs')->count();
            }
            if (DB::getSchemaBuilder()->hasTable('failed_jobs')) {
                $queueStatus['failed_jobs'] = DB::table('failed_jobs')->count();
            }
            if ($queueStatus['failed_jobs'] > 0) {
                $status = 'warning'; // 有失败任务时提示警告
            }
        } catch (\Exception $e) {
            // 表不存在或报错忽略
        }

        return response()->json([
            'code' => 200,
            'message' => 'pong',
            'data' => [
                'system_status' => $status,
                'database' => $dbStatus,
                'queue' => $queueStatus,
                'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
                'server_time' => now()->toDateTimeString(),
            ]
        ]);
    }
}
