import { useState, useEffect } from 'react'
import { products } from '../api/client'
import { getMockProducts } from '../data/mockProducts'
import ProductCard from '../components/ProductCard'
import './Home.css'

export default function Home() {
  const [list, setList] = useState([])
  const [loading, setLoading] = useState(true)
  const [filtersOpen, setFiltersOpen] = useState(false)
  const [useMock, setUseMock] = useState(false)

  useEffect(() => {
    products
      .getAll()
      .then((data) => {
        const items = Array.isArray(data) ? data : []
        if (items.length > 0) {
          setList(items)
          setUseMock(false)
          return
        }
        return getMockProducts().then((mock) => {
          setList(mock)
          setUseMock(true)
        })
      })
      .catch(() =>
        getMockProducts().then((mock) => {
          setList(mock)
          setUseMock(true)
        })
      )
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

        <section id="products" className="products-section">
          {list.length === 0 ? (
            <p className="no-products">No products available.</p>
          ) : (
            <div className="product-grid product-grid-unified">
              {list.map((p, index) => (
                <ProductCard key={p.id} product={p} gradientIndex={index} />
              ))}
            </div>
          )}
        </section>
      </div>
    </div>
  )
}
