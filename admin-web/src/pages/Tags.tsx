import { useEffect, useState } from 'react';
import request from '../utils/request';
import { Trash2 } from 'lucide-react';

export default function Tags() {
  const [tags, setTags] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);

  const fetchTags = async () => {
    setLoading(true);
    const res: any = await request.get('/api/admin/tags');
    if (res.code === 200) setTags(res.data.data); // 分页数据在 data.data
    setLoading(false);
  };

  useEffect(() => {
    fetchTags();
  }, []);

  const deleteTag = async (id: number) => {
    if (!window.confirm('删除标签将解除其与所有壁纸的关联，确定要删除吗？')) return;
    const res: any = await request.delete(`/api/admin/tags/${id}`);
    if (res.code === 200) fetchTags();
  };

  return (
    <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div className="p-6 border-b border-gray-100 flex justify-between items-center">
        <div>
          <h3 className="text-lg font-medium text-gray-900">AI 标签管理</h3>
          <p className="text-sm text-gray-500 mt-1">管理由 AI 视觉大模型自动生成的壁纸标签内容。</p>
        </div>
      </div>

      <table className="w-full text-left border-collapse">
        <thead>
          <tr className="bg-gray-50/50 text-gray-500 text-sm border-b border-gray-100">
            <th className="p-4 font-medium">标签名称</th>
            <th className="p-4 font-medium">关联壁纸数量</th>
            <th className="p-4 font-medium">首次打标时间</th>
            <th className="p-4 font-medium text-center">操作</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-gray-50">
          {loading ? (
            <tr><td colSpan={4} className="p-8 text-center text-gray-400">加载中...</td></tr>
          ) : tags.length === 0 ? (
            <tr><td colSpan={4} className="p-8 text-center text-gray-400">暂无 AI 生成的标签</td></tr>
          ) : tags.map(t => (
            <tr key={t.id} className="hover:bg-gray-50/50 transition-colors text-sm">
              <td className="p-4">
                <span className="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full font-medium">#{t.name}</span>
              </td>
              <td className="p-4 text-gray-600 font-medium">{t.wallpapers_count} 张</td>
              <td className="p-4 text-gray-500">{new Date(t.created_at).toLocaleString()}</td>
              <td className="p-4 text-center">
                <button onClick={() => deleteTag(t.id)} className="p-1.5 text-gray-400 hover:text-red-500 transition-colors" title="删除标签">
                  <Trash2 className="w-4 h-4 inline-block" />
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
