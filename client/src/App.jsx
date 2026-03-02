import { Routes, Route, Navigate } from 'react-router-dom'
import { useAuth } from './context/AuthContext'
import Layout from './components/Layout'
import Home from './pages/Home'
import Login from './pages/Login'
import Signup from './pages/Signup'
import ProductDetail from './pages/ProductDetail'
import Cart from './pages/Cart'
import Checkout from './pages/Checkout'
import Account from './pages/Account'
import Orders from './pages/Orders'
import Admin from './pages/Admin'

function PrivateRoute({ children }) {
  const { token, loading } = useAuth()
  if (loading) return <div className="container" style={{ padding: '2rem', textAlign: 'center' }}>Loading...</div>
  if (!token) return <Navigate to="/login" replace />
  return children
}

function AdminRoute({ children }) {
  const { token, user, loading } = useAuth()
  if (loading) return <div className="container" style={{ padding: '2rem', textAlign: 'center' }}>Loading...</div>
  if (!token) return <Navigate to="/login" replace />
  if (user?.userType !== 'admin') return <Navigate to="/" replace />
  return children
}

export default function App() {
  return (
    <Routes>
      <Route path="/" element={<Layout />}>
        <Route index element={<Home />} />
        <Route path="login" element={<Login />} />
        <Route path="signup" element={<Signup />} />
        <Route path="product/:id" element={<ProductDetail />} />
        <Route path="cart" element={<Cart />} />
        <Route path="checkout" element={<PrivateRoute><Checkout /></PrivateRoute>} />
        <Route path="checkout/:id" element={<PrivateRoute><Checkout /></PrivateRoute>} />
        <Route path="account" element={<PrivateRoute><Account /></PrivateRoute>} />
        <Route path="orders" element={<PrivateRoute><Orders /></PrivateRoute>} />
        <Route path="admin" element={<AdminRoute><Admin /></AdminRoute>} />
        <Route path="*" element={<Navigate to="/" replace />} />
      </Route>
    </Routes>
  )
}
