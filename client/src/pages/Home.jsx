import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { products } from '../api/client'
import { getMockProducts } from '../data/mockProducts'
import './Home.css'

const CARD_GRADIENTS = [
  'linear-gradient(135deg, #fefce8 0%, #fef9c3 100%)',
  'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)',
  'linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%)',
  'linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%)',
  'linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%)',
  'linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%)',
]

export default function Home() {
  const [list, setList] = useState([])
  const [loading, setLoading] = useState(true)
  const [filtersOpen, setFiltersOpen] = useState(false)
  const [useMock, setUseMock] = useState(false)

  useEffect(() => {
    products
      .getAll()
      .then((data) => {
        setList(Array.isArray(data) ? data : [])
        setUseMock(false)
      })
      .catch(() => {
        getMockProducts().then(setList)
        setUseMock(true)
      })
      .finally(() => setLoading(false))
  }, [])

  if (loading) {
    return (
      <div className="home">
        <div className="container">
          <div className="home-loading">Loading...</div>
        </div>
      </div>
    )
  }

  return (
    <div className="home">
      <div className="container">
        {/* Show Filters bar - Lassana style */}
        <section className="filters-bar">
          <button
            type="button"
            className="filters-toggle"
            onClick={() => setFiltersOpen(!filtersOpen)}
            aria-expanded={filtersOpen}
          >
            <span className="filters-arrow">{filtersOpen ? '▲' : '▼'}</span>
            Show Filters
          </button>
          {useMock && (
            <span className="mock-badge">Demo data — connect backend for live products</span>
          )}
        </section>

        {filtersOpen && (
          <div className="filters-panel">
            <p className="filters-placeholder">Category, price range, and more filters coming soon.</p>
          </div>
        )}

        {/* Product grid - Lassana-style cards */}
        <section id="products" className="products-section">
          {list.length === 0 ? (
            <p className="no-products">No products available.</p>
          ) : (
            <div className="product-grid product-grid-lassana">
              {list.map((p, index) => (
                <Link
                  key={p.id}
                  to={`/product/${p.id}`}
                  className="product-card product-card-lassana"
                  style={{ background: CARD_GRADIENTS[index % CARD_GRADIENTS.length] }}
                >
                  <div className="product-card-image-wrap">
                    <img
                      src={p.imagePath || 'https://via.placeholder.com/300x200?text=No+Image'}
                      alt={p.name}
                      className="product-card-image"
                      onError={(e) => {
                        e.target.src = 'https://via.placeholder.com/300x200?text=No+Image'
                      }}
                    />
                    {p.bestSeller && (
                      <span className="product-tag product-tag-bestseller">Best Seller</span>
                    )}
                  </div>
                  <div className="product-card-body">
                    <h3 className="product-card-name">{p.name.toUpperCase()}</h3>
                    <p className="product-card-price">
                      ${Number(p.price).toLocaleString('en-US', { minimumFractionDigits: 2 })}
                    </p>
                  </div>
                </Link>
              ))}
            </div>
          )}
        </section>
      </div>
    </div>
  )
}
