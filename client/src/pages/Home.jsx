import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { products } from '../api/client'
import './Home.css'

export default function Home() {
  const [list, setList] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  useEffect(() => {
    products.getAll()
      .then(setList)
      .catch((e) => setError(e.message))
      .finally(() => setLoading(false))
  }, [])

  if (loading) return <div className="container">Loading products...</div>
  if (error) return <div className="container alert-error">{error}</div>

  return (
    <div className="container">
      <h1>Products</h1>
      <div className="product-grid">
        {list.length === 0 ? (
          <p>No products available.</p>
        ) : (
          list.map((p) => (
            <div key={p.id} className="product-card">
              <img
                src={p.imagePath || '/placeholder.png'}
                alt={p.name}
                className="product-image"
                onError={(e) => { e.target.src = 'https://via.placeholder.com/300x200?text=No+Image' }}
              />
              <div className="product-details">
                <h3 className="product-name">{p.name}</h3>
                <p className="product-category">Category: {p.category || '—'}</p>
                <p className="product-price">${Number(p.price).toFixed(2)}</p>
                <p className="product-description">
                  {p.description ? (p.description.slice(0, 60) + (p.description.length > 60 ? '...' : '')) : '—'}
                </p>
                <Link to={`/product/${p.id}`} className="btn">Buy Now</Link>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  )
}
