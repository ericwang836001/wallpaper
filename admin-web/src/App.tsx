import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import Layout from './components/Layout';
import Dashboard from './pages/Dashboard';
import Devices from './pages/Devices';

// 鉴权路由保护组件
const PrivateRoute = ({ children }: { children: JSX.Element }) => {
  const token = localStorage.getItem('admin_token');
  return token ? children : <Navigate to="/login" />;
};

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/login" element={<Login />} />
        
        <Route path="/" element={<PrivateRoute><Layout /></PrivateRoute>}>
          <Route index element={<Dashboard />} />
          <Route path="wallpapers" element={<div className="p-8 text-gray-500 text-center">壁纸列表正在开发中...</div>} />
          <Route path="categories" element={<div className="p-8 text-gray-500 text-center">分类管理正在开发中...</div>} />
          <Route path="devices" element={<Devices />} />
        </Route>
      </Routes>
    </Router>
  );
}

export default App;
