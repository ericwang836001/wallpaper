import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom';
import { LayoutDashboard, Image as ImageIcon, Smartphone, LogOut, Tags } from 'lucide-react';
import request from '../utils/request';

export default function Layout() {
  const location = useLocation();
  const navigate = useNavigate();
  const user = JSON.parse(localStorage.getItem('admin_user') || '{}');

  const handleLogout = async () => {
    try {
      await request.post('/api/admin/auth/logout');
    } catch (e) {}
    localStorage.removeItem('admin_token');
    localStorage.removeItem('admin_user');
    navigate('/login');
  };

  const navs = [
    { name: '数据概览', path: '/', icon: LayoutDashboard },
    { name: '壁纸管理', path: '/wallpapers', icon: ImageIcon },
    { name: '分类管理', path: '/categories', icon: Tags },
    { name: '设备管理', path: '/devices', icon: Smartphone },
  ];

  return (
    <div className="flex h-screen bg-gray-50">
      <aside className="w-64 bg-white border-r border-gray-200 flex flex-col">
        <div className="h-16 flex items-center px-6 border-b border-gray-200">
          <h1 className="text-lg font-bold text-gray-800">Wallpaper OS</h1>
        </div>
        <nav className="flex-1 p-4 space-y-1 overflow-y-auto">
          {navs.map(nav => {
            const Icon = nav.icon;
            const isActive = location.pathname === nav.path;
            return (
              <Link 
                key={nav.path} 
                to={nav.path}
                className={`flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors ${isActive ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50'}`}
              >
                <Icon className="w-5 h-5" />
                {nav.name}
              </Link>
            )
          })}
        </nav>
        <div className="p-4 border-t border-gray-200">
          <div className="flex items-center gap-3 px-3 py-2">
            <div className="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
              {user.username?.[0]?.toUpperCase()}
            </div>
            <div className="flex-1 truncate">
              <p className="text-sm font-medium text-gray-800">{user.name || user.username}</p>
              <p className="text-xs text-gray-500 truncate">{user.role}</p>
            </div>
            <button onClick={handleLogout} className="p-1.5 text-gray-400 hover:text-red-500 rounded-md transition-colors">
              <LogOut className="w-4 h-4" />
            </button>
          </div>
        </div>
      </aside>

      <main className="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header className="h-16 bg-white border-b border-gray-200 flex items-center px-8 z-10">
          <h2 className="text-lg font-medium text-gray-800">
            {navs.find(n => n.path === location.pathname)?.name || '控制台'}
          </h2>
        </header>
        <div className="flex-1 overflow-y-auto p-8">
          <Outlet />
        </div>
      </main>
    </div>
  );
}
