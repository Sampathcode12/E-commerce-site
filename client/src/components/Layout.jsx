import { useState } from 'react'
import { Outlet, Link } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { categories, categorySubcategories } from '../data/mockProducts'
import './Layout.css'

export default function Layout() {
  const { user, token, logout } = useAuth()
  const [searchQuery, setSearchQuery] = useState('')
  const [categoriesOpen, setCategoriesOpen] = useState(false)
  const [hoveredCategory, setHoveredCategory] = useState(null)
  const cartCount = 0

  return (
    <div className="layout">
      {/* Top bar - Lassana style */}
      <header className="header header-top">
        <div className="container header-inner">
          <Link to="/" className="logo">
            <span className="logo-icon">🛒</span>
            <span>E-Commerce</span>
          </Link>

          <div className="header-search">
            <input
              type="search"
              placeholder="Search for anything..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="search-input"
            />
            <span className="search-icon" aria-hidden>🔍</span>
          </div>

          <div className="header-right">
            <span className="hotline">
              <span className="hotline-label">Need help?</span>
              <a href="tel:0112001122" className="hotline-number">011 200 1122</a>
            </span>
            <Link to={token ? '/account' : '/login'} className="header-link header-account">
              <span className="header-icon" aria-hidden>👤</span>
              <span>Account</span>
            </Link>
            <Link to="/" className="header-link header-cart">
              <span className="header-icon" aria-hidden>🛒</span>
              <span>Cart</span>
              {cartCount > 0 && <span className="cart-badge">{cartCount}</span>}
            </Link>
          </div>
        </div>
      </header>

      {/* Green nav bar with category hover mega menus */}
      <nav className="nav-bar">
        <div className="container nav-inner">
          <div
            className="nav-categories-wrap"
            onMouseEnter={() => setCategoriesOpen(true)}
            onMouseLeave={() => setCategoriesOpen(false)}
          >
            <button type="button" className="nav-categories-btn" aria-expanded={categoriesOpen}>
              <span className="nav-categories-icon">▦</span>
              <span>Categories</span>
              <span className="nav-arrow">▼</span>
            </button>
            {categoriesOpen && (
              <div className="nav-dropdown">
                {categories.map((cat) => (
                  <Link
                    key={cat.id}
                    to={`/#products`}
                    className="nav-dropdown-item"
                    onClick={() => setCategoriesOpen(false)}
                  >
                    <span className="nav-dropdown-icon">{cat.icon}</span>
                    {cat.name}
                  </Link>
                ))}
              </div>
            )}
          </div>
          <div className="nav-links">
            <Link to="/" className="nav-link">All Products</Link>
            {categories.map((cat) => {
              const subcategories = categorySubcategories[cat.id]
              const hasSub = subcategories && subcategories.length > 0
              return hasSub ? (
                <div
                  key={cat.id}
                  className="nav-link-with-mega"
                  onMouseEnter={() => setHoveredCategory(cat.id)}
                  onMouseLeave={() => setHoveredCategory(null)}
                >
                  <Link
                    to={`/#products`}
                    className={`nav-link ${hoveredCategory === cat.id ? 'nav-link-active' : ''}`}
                  >
                    {cat.name}
                  </Link>
                  {hoveredCategory === cat.id && (
                    <div className="nav-mega">
                      <div className="container nav-mega-inner">
                        {subcategories.map((sub, i) => (
                          <div key={i} className="nav-mega-column">
                            <div className="nav-mega-title">{sub.name}</div>
                            {sub.children && sub.children.length > 0 && (
                              <ul className="nav-mega-list">
                                {sub.children.map((child, j) => (
                                  <li key={j}>
                                    <Link
                                      to={`/#products`}
                                      className="nav-mega-link"
                                      onClick={() => setHoveredCategory(null)}
                                    >
                                      {child}
                                    </Link>
                                  </li>
                                ))}
                              </ul>
                            )}
                          </div>
                        ))}
                      </div>
                    </div>
                  )}
                </div>
              ) : (
                <Link key={cat.id} to={`/#products`} className="nav-link">
                  {cat.name}
                </Link>
              )
            })}
            <Link to="/#deals" className="nav-link">Daily Deals</Link>
          </div>
          <div className="nav-actions">
            {token ? (
              <>
                <Link to="/orders" className="nav-link">Orders</Link>
                <span className="nav-user">{user?.email}</span>
                <button type="button" className="btn btn-nav" onClick={logout}>
                  Logout
                </button>
              </>
            ) : (
              <>
                <Link to="/login" className="nav-link">Login</Link>
                <Link to="/signup" className="btn btn-nav btn-nav-primary">Sign up</Link>
              </>
            )}
            <Link to="/orders" className="btn btn-nav btn-order-status">Order Status</Link>
          </div>
        </div>
      </nav>

      <main className="main">
        <Outlet />
      </main>

      <footer className="footer">
        <div className="container">
          &copy; {new Date().getFullYear()} E-Commerce. All rights reserved.
        </div>
      </footer>

      <a href="#help" className="fab-chat" aria-label="Help / Chat">
        <span className="fab-chat-icon">💬</span>
      </a>
    </div>
  )
}
