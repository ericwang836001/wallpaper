import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import Layout from './components/Layout';
import Dashboard from './pages/Dashboard';
import Devices from './pages/Devices';
import Categories from './pages/Categories';
import Tags from './pages/Tags';
import Wallpapers from './pages/Wallpapers';

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
          <Route path="wallpapers" element={<Wallpapers />} />
          <Route path="categories" element={<Categories />} />
          <Route path="tags" element={<Tags />} />
          <Route path="devices" element={<Devices />} />
        </Route>
      </Routes>
    </Router>
  );
}

export default App;
