import { Outlet } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { Link } from 'react-router-dom'
import './Layout.css'

export default function Layout() {
  const { user, token, logout } = useAuth()

  return (
    <div className="layout">
      <header className="header">
        <div className="container header-inner">
          <Link to="/" className="logo">E-Commerce</Link>
          <nav>
            <Link to="/">Home</Link>
            {token ? (
              <>
                <Link to="/orders">Orders</Link>
                <Link to="/account">Account</Link>
                <span className="user-email">{user?.email}</span>
                <button type="button" className="btn btn-secondary" onClick={logout}>Logout</button>
              </>
            ) : (
              <>
                <Link to="/login">Login</Link>
                <Link to="/signup" className="btn">Sign up</Link>
              </>
            )}
          </nav>
        </div>
      </header>
      <main className="main">
        <Outlet />
      </main>
      <footer className="footer">
        <div className="container">&copy; {new Date().getFullYear()} E-Commerce. All rights reserved.</div>
      </footer>
    </div>
  )
}
