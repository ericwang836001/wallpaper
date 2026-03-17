import { useEffect, useState } from 'react';
import request from '../utils/request';
import { Activity, AlertTriangle, CheckCircle2 } from 'lucide-react';

export default function SystemHeartbeat() {
  const [health, setHealth] = useState<any>(null);
  const [isError, setIsError] = useState(false);

  const checkHeartbeat = async () => {
    try {
      const res: any = await request.get('/api/admin/system/heartbeat');
      if (res.code === 200) {
        setHealth(res.data);
        setIsError(false);
      }
    } catch (e) {
      setIsError(true);
    }
  };

  useEffect(() => {
    checkHeartbeat();
    const timer = setInterval(checkHeartbeat, 15000); // 每 15 秒探活一次
    return () => clearInterval(timer);
  }, []);

  if (isError) {
    return (
      <div className="flex items-center gap-2 text-red-500 text-xs font-medium bg-red-50 px-3 py-1.5 rounded-full">
        <span className="relative flex h-2 w-2">
          <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
          <span className="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
        </span>
        后端已断开
      </div>
    );
  }

  if (!health) return null;

  const isHealthy = health.system_status === 'healthy';

  return (
    <div className="flex items-center gap-4 text-xs">
      <div className="flex flex-col items-end">
        <span className="text-gray-400">队列等待: {health.queue.pending_jobs}</span>
        {health.queue.failed_jobs > 0 && <span className="text-red-500">失败任务: {health.queue.failed_jobs}</span>}
      </div>
      <div className={`flex items-center gap-2 px-3 py-1.5 rounded-full font-medium ${isHealthy ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600'}`}>
        <span className="relative flex h-2 w-2">
          <span className={`animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 ${isHealthy ? 'bg-green-400' : 'bg-orange-400'}`}></span>
          <span className={`relative inline-flex rounded-full h-2 w-2 ${isHealthy ? 'bg-green-500' : 'bg-orange-500'}`}></span>
        </span>
        {isHealthy ? '服务健康' : '运行警告'}
      </div>
    </div>
  );
}
