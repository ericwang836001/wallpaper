import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import request from '../utils/request';

export default function Login() {
  const [username, setUsername] = useState('admin');
  const [password, setPassword] = useState('admin123');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    try {
      const res: any = await request.post('/api/admin/auth/login', { username, password });
      if (res.code === 200) {
        localStorage.setItem('admin_token', res.data.token);
        localStorage.setItem('admin_user', JSON.stringify(res.data.user));
        navigate('/');
      } else {
        setError(res.message);
      }
    } catch (err: any) {
      setError(err.response?.data?.message || '登录失败');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <div className="bg-white p-8 rounded-2xl shadow-sm w-full max-w-md">
        <div className="text-center mb-8">
          <h1 className="text-2xl font-bold text-gray-900">Wallpaper Admin</h1>
          <p className="text-gray-500 mt-2">登录管理后台</p>
        </div>
        <form onSubmit={handleLogin} className="space-y-5">
          {error && <div className="text-red-500 text-sm bg-red-50 p-3 rounded-lg">{error}</div>}
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">用户名</label>
            <input 
              type="text" 
              value={username}
              onChange={e => setUsername(e.target.value)}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">密码</label>
            <input 
              type="password" 
              value={password}
              onChange={e => setPassword(e.target.value)}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
            />
          </div>
          <button 
            type="submit" 
            disabled={loading}
            className="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition-colors disabled:opacity-50"
          >
            {loading ? '登录中...' : '登 录'}
          </button>
        </form>
      </div>
    </div>
  );
}
