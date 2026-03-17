import { useEffect, useState, useRef } from 'react';
import request from '../utils/request';
import { Upload, MoreVertical, Trash2, CheckCircle, XCircle, EyeOff } from 'lucide-react';

const STATUS_MAP: any = {
  0: { label: '处理中', color: 'bg-orange-100 text-orange-700' },
  1: { label: '已发布', color: 'bg-green-100 text-green-700' },
  2: { label: '已下架', color: 'bg-gray-100 text-gray-700' },
  3: { label: '被拒绝', color: 'bg-red-100 text-red-700' },
};

export default function Wallpapers() {
  const [wallpapers, setWallpapers] = useState<any[]>([]);
  const [categories, setCategories] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState({ status: '', category_id: '' });
  const [uploading, setUploading] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const fetchData = async () => {
    setLoading(true);
    let url = '/api/admin/wallpapers?per_page=20';
    if (filter.status !== '') url += `&status=${filter.status}`;
    if (filter.category_id !== '') url += `&category_id=${filter.category_id}`;
    
    const [wpRes, catRes]: any = await Promise.all([
      request.get(url),
      categories.length === 0 ? request.get('/api/admin/categories') : Promise.resolve(null)
    ]);
    
    if (wpRes.code === 200) setWallpapers(wpRes.data.data);
    if (catRes?.code === 200) setCategories(catRes.data);
    setLoading(false);
  };

  useEffect(() => {
    fetchData();
  }, [filter]);

  const handleUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);
    // 默认放到第一个分类中，后续可优化上传弹窗
    if (categories.length > 0) formData.append('category_id', categories[0].id);

    setUploading(true);
    try {
      const res: any = await request.post('/api/admin/wallpapers/upload', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      if (res.code === 200) {
        alert('上传成功！后台正在裁切生成各分辨率壁纸。');
        fetchData(); // 刷新列表查看处理中状态
      }
    } catch (err) {
      alert('上传失败');
    } finally {
      setUploading(false);
      if (fileInputRef.current) fileInputRef.current.value = '';
    }
  };

  const changeStatus = async (id: number, status: number) => {
    const res: any = await request.put(`/api/admin/wallpapers/${id}/status`, { status });
    if (res.code === 200) fetchData();
  };

  const deleteWallpaper = async (id: number) => {
    if (!window.confirm('确定要删除这张壁纸吗？')) return;
    const res: any = await request.delete(`/api/admin/wallpapers/${id}`);
    if (res.code === 200) fetchData();
  };

  return (
    <div className="space-y-6">
      {/* 头部控制栏 */}
      <div className="flex flex-wrap gap-4 justify-between items-center bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <div className="flex items-center gap-4">
          <select 
            value={filter.category_id} 
            onChange={e => setFilter({...filter, category_id: e.target.value})}
            className="px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none bg-gray-50"
          >
            <option value="">全部分类</option>
            {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
          </select>
          <select 
            value={filter.status} 
            onChange={e => setFilter({...filter, status: e.target.value})}
            className="px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none bg-gray-50"
          >
            <option value="">全部状态</option>
            <option value="0">处理中</option>
            <option value="1">已发布</option>
            <option value="2">已下架</option>
            <option value="3">被拒绝</option>
          </select>
        </div>
        
        <input type="file" ref={fileInputRef} onChange={handleUpload} accept="image/*" className="hidden" />
        <button 
          onClick={() => fileInputRef.current?.click()}
          disabled={uploading}
          className="flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors disabled:opacity-50"
        >
          <Upload className="w-4 h-4" />
          {uploading ? '上传中...' : '上传新壁纸'}
        </button>
      </div>

      {/* 壁纸瀑布流网格 */}
      {loading ? (
        <div className="text-center text-gray-400 py-12">加载中...</div>
      ) : wallpapers.length === 0 ? (
        <div className="text-center text-gray-400 py-12 bg-white rounded-2xl border border-gray-100">没有找到匹配的壁纸</div>
      ) : (
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
          {wallpapers.map(w => {
            // 尝试获取缩略图，如果没有则用原图（通常是上传刚完成还在处理中）
            const thumb = w.variants?.find((v:any) => v.type === 1)?.url;
            const imgUrl = thumb ? `/storage/${thumb}` : `/storage/${w.original_url}`;
            const statusConfig = STATUS_MAP[w.status];

            return (
              <div key={w.id} className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group">
                <div className="aspect-[9/16] bg-gray-100 relative overflow-hidden">
                  <img src={imgUrl} alt={w.title} className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                  <div className={`absolute top-3 left-3 px-2 py-1 rounded text-xs font-medium ${statusConfig.color}`}>
                    {statusConfig.label}
                  </div>
                  
                  {/* 悬浮操作面板 */}
                  <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                    {w.status === 0 && <span className="text-white text-sm">后台疯狂裁剪中...</span>}
                    {w.status !== 0 && (
                      <>
                        <button onClick={() => changeStatus(w.id, 1)} title="发布" className="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600"><CheckCircle className="w-4 h-4"/></button>
                        <button onClick={() => changeStatus(w.id, 2)} title="下架" className="w-8 h-8 rounded-full bg-gray-500 text-white flex items-center justify-center hover:bg-gray-600"><EyeOff className="w-4 h-4"/></button>
                        <button onClick={() => changeStatus(w.id, 3)} title="拒绝" className="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center hover:bg-orange-600"><XCircle className="w-4 h-4"/></button>
                        <button onClick={() => deleteWallpaper(w.id)} title="删除" className="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600"><Trash2 className="w-4 h-4"/></button>
                      </>
                    )}
                  </div>
                </div>
                <div className="p-4">
                  <h4 className="text-sm font-medium text-gray-900 truncate">{w.title || '未命名壁纸'}</h4>
                  <div className="flex justify-between items-center mt-2">
                    <span className="text-xs text-gray-500">{w.category?.name || '未分类'}</span>
                    <span className="text-xs text-gray-400">{(w.original_size / 1024 / 1024).toFixed(1)} MB</span>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
}
