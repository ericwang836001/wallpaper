import { useEffect, useState } from 'react';
import request from '../utils/request';
import { Plus } from 'lucide-react';

export default function Categories() {
  const [categories, setCategories] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [formData, setFormData] = useState({ name: '', slug: '', sort_order: 0 });

  const fetchCategories = async () => {
    setLoading(true);
    const res: any = await request.get('/api/admin/categories');
    if (res.code === 200) setCategories(res.data);
    setLoading(false);
  };

  useEffect(() => {
    fetchCategories();
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const res: any = await request.post('/api/admin/categories', formData);
    if (res.code === 200) {
      setShowForm(false);
      setFormData({ name: '', slug: '', sort_order: 0 });
      fetchCategories();
    } else {
      alert(res.message || '添加失败');
    }
  };

  return (
    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div className="p-6 border-b border-gray-100 flex justify-between items-center">
        <div>
          <h3 className="text-lg font-medium text-gray-900">分类管理</h3>
          <p className="text-sm text-gray-500 mt-1">管理壁纸的分类标签及前端展示排序。</p>
        </div>
        <button 
          onClick={() => setShowForm(!showForm)}
          className="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
        >
          <Plus className="w-4 h-4" />
          新增分类
        </button>
      </div>

      {showForm && (
        <div className="p-6 bg-gray-50 border-b border-gray-100">
          <form onSubmit={handleSubmit} className="flex items-end gap-4 max-w-3xl">
            <div className="flex-1">
              <label className="block text-xs font-medium text-gray-700 mb-1">分类名称</label>
              <input type="text" required value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} className="w-full px-3 py-2 border rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500" placeholder="例如：动物世界" />
            </div>
            <div className="flex-1">
              <label className="block text-xs font-medium text-gray-700 mb-1">标识别名 (Slug)</label>
              <input type="text" required value={formData.slug} onChange={e => setFormData({...formData, slug: e.target.value})} className="w-full px-3 py-2 border rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500" placeholder="例如：animals" />
            </div>
            <div className="w-24">
              <label className="block text-xs font-medium text-gray-700 mb-1">排序权重</label>
              <input type="number" value={formData.sort_order} onChange={e => setFormData({...formData, sort_order: parseInt(e.target.value)})} className="w-full px-3 py-2 border rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <button type="submit" className="bg-green-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
              保存
            </button>
          </form>
        </div>
      )}

      <table className="w-full text-left border-collapse">
        <thead>
          <tr className="bg-gray-50/50 text-gray-500 text-sm border-b border-gray-100">
            <th className="p-4 font-medium">ID</th>
            <th className="p-4 font-medium">分类名称</th>
            <th className="p-4 font-medium">别名 (Slug)</th>
            <th className="p-4 font-medium">排序权重</th>
            <th className="p-4 font-medium">创建时间</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-gray-50">
          {loading ? (
            <tr><td colSpan={5} className="p-8 text-center text-gray-400">加载中...</td></tr>
          ) : categories.map(c => (
            <tr key={c.id} className="hover:bg-gray-50/50 transition-colors text-sm">
              <td className="p-4 text-gray-500">{c.id}</td>
              <td className="p-4 font-medium text-gray-900">{c.name}</td>
              <td className="p-4 text-gray-500 bg-gray-100 rounded px-2 font-mono text-xs inline-block mt-3 ml-4">{c.slug}</td>
              <td className="p-4 text-gray-600">{c.sort_order}</td>
              <td className="p-4 text-gray-500">{new Date(c.created_at).toLocaleString()}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
