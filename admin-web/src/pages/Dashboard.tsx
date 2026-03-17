import { useEffect, useState } from 'react';
import request from '../utils/request';
import { Image, Clock, Users, Smartphone } from 'lucide-react';

export default function Dashboard() {
  const [stats, setStats] = useState({
    total_wallpapers: 0,
    pending_wallpapers: 0,
    total_users: 0,
    active_devices: 0
  });

  useEffect(() => {
    request.get('/api/admin/dashboard/stats').then((res: any) => {
      if (res.code === 200) setStats(res.data);
    });
  }, []);

  const cards = [
    { title: '壁纸总数', value: stats.total_wallpapers, icon: Image, color: 'text-blue-600', bg: 'bg-blue-50' },
    { title: '待审核 (处理中)', value: stats.pending_wallpapers, icon: Clock, color: 'text-orange-600', bg: 'bg-orange-50' },
    { title: '用户总数', value: stats.total_users, icon: Users, color: 'text-green-600', bg: 'bg-green-50' },
    { title: '活跃适配设备', value: stats.active_devices, icon: Smartphone, color: 'text-purple-600', bg: 'bg-purple-50' },
  ];

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      {cards.map((card, i) => {
        const Icon = card.icon;
        return (
          <div key={i} className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5">
            <div className={`w-14 h-14 rounded-xl flex items-center justify-center ${card.bg}`}>
              <Icon className={`w-7 h-7 ${card.color}`} />
            </div>
            <div>
              <p className="text-sm text-gray-500 font-medium">{card.title}</p>
              <h3 className="text-2xl font-bold text-gray-900 mt-1">{card.value}</h3>
            </div>
          </div>
        )
      })}
    </div>
  );
}
