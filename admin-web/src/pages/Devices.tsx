import { useEffect, useState } from 'react';
import request from '../utils/request';

export default function Devices() {
  const [devices, setDevices] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);

  const fetchDevices = async () => {
    setLoading(true);
    const res: any = await request.get('/api/admin/devices?per_page=100');
    if (res.code === 200) setDevices(res.data.data);
    setLoading(false);
  };

  useEffect(() => {
    fetchDevices();
  }, []);

  const toggleActive = async (id: number) => {
    const res: any = await request.put(`/api/admin/devices/${id}/toggle-active`);
    if (res.code === 200) {
      setDevices(devices.map(d => d.id === id ? { ...d, is_active: !d.is_active } : d));
    }
  };

  return (
    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div className="p-6 border-b border-gray-100 flex justify-between items-center">
        <div>
          <h3 className="text-lg font-medium text-gray-900">设备适配字典</h3>
          <p className="text-sm text-gray-500 mt-1">控制后台上传图片时，自动为哪些设备生成专属分辨率壁纸。</p>
        </div>
      </div>
      <div className="overflow-x-auto">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="bg-gray-50/50 text-gray-500 text-sm border-b border-gray-100">
              <th className="p-4 font-medium">设备名称</th>
              <th className="p-4 font-medium">品牌</th>
              <th className="p-4 font-medium">类型</th>
              <th className="p-4 font-medium">分辨率 (宽 x 高)</th>
              <th className="p-4 font-medium">系统</th>
              <th className="p-4 font-medium text-center">状态 (点击切换)</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-50">
            {loading ? (
              <tr><td colSpan={6} className="p-8 text-center text-gray-400">加载中...</td></tr>
            ) : devices.map(d => (
              <tr key={d.id} className="hover:bg-gray-50/50 transition-colors text-sm">
                <td className="p-4 font-medium text-gray-900">{d.name}</td>
                <td className="p-4 text-gray-600">{d.brand}</td>
                <td className="p-4 text-gray-500 capitalize">{d.type}</td>
                <td className="p-4 text-gray-900">{d.screen_width} x {d.screen_height}</td>
                <td className="p-4 text-gray-500">{d.os_family}</td>
                <td className="p-4 text-center">
                  <button 
                    onClick={() => toggleActive(d.id)}
                    className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none ${d.is_active ? 'bg-blue-600' : 'bg-gray-200'}`}
                  >
                    <span className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform ${d.is_active ? 'translate-x-6' : 'translate-x-1'}`} />
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
