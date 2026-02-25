import { useState, useEffect } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { products } from '../api/client'
import { getMockProductById } from '../data/mockProducts'
import './ProductDetail.css'

export default function ProductDetail() {
  const { id } = useParams()
  const navigate = useNavigate()
  const { token } = useAuth()
  const [product, setProduct] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  useEffect(() => {
    setError(null)
    products
      .getById(id)
      .then((p) => {
        setProduct(p)
        setError(null)
      })
      .catch(() =>
        getMockProductById(id)
          .then(setProduct)
          .catch((e) => setError(e?.message || 'Product not found'))
      )
      .finally(() => setLoading(false))
  }, [id])

  const handleBuyNow = () => {
    if (!token) {
      navigate('/login')
      return
    }
    navigate(`/checkout/${id}`)
  }

  if (loading) return <div className="container">Loading...</div>
  if (error || !product) return <div className="container alert-error">{error || 'Product not found'}</div>

  return (
    <div className="container product-detail-page">
      <div className="product-detail-card">
        <img
          src={product.imagePath || 'https://via.placeholder.com/400x300?text=No+Image'}
          alt={product.name}
          className="product-detail-image"
        />
        <div className="product-detail-info">
          <h1>{product.name}</h1>
          <p className="meta">Category: {product.category || '—'} · Stock: {product.quantity}</p>
          <p className="price">${Number(product.price).toFixed(2)}</p>
          <p className="description">{product.description || 'No description.'}</p>
          <button type="button" className="btn" onClick={handleBuyNow}>
            Buy Now
          </button>
        </div>
      </div>
    </div>
  )
}
