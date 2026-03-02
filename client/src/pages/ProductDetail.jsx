import { useState, useEffect } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { useCart } from '../context/CartContext'
import { products } from '../api/client'
import { getMockProductById, getPlaceholderImageUrl } from '../data/mockProducts'
import { formatPrice } from '../utils/formatPrice'
import './ProductDetail.css'

export default function ProductDetail() {
  const { id } = useParams()
  const navigate = useNavigate()
  const { token } = useAuth()
  const { addItem } = useCart()
  const [product, setProduct] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const [quantity, setQuantity] = useState(1)
  const [addedToCart, setAddedToCart] = useState(false)

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

  useEffect(() => {
    if (product) setQuantity((q) => Math.min(q, product.quantity ?? 999))
  }, [product])

  const maxQty = product?.quantity ?? 999

  const handleAddToCart = () => {
    if (!product) return
    addItem(product, quantity)
    setAddedToCart(true)
    setTimeout(() => setAddedToCart(false), 2000)
  }

  const handleBuy = () => {
    if (!product) return
    addItem(product, quantity)
    navigate('/cart')
  }

  if (loading) return <div className="container">Loading...</div>
  if (error || !product) return <div className="container alert-error">{error || 'Product not found'}</div>

  return (
    <div className="container product-detail-page">
      <div className="product-detail-card">
        <img
          src={product.imagePath || getPlaceholderImageUrl(product.id, 400, 300)}
          alt={product.name}
          className="product-detail-image"
          onError={(e) => {
            e.target.src = getPlaceholderImageUrl(product.id, 400, 300)
          }}
        />
        <div className="product-detail-info">
          <h1>{product.name}</h1>
          <p className="meta">Category: {product.category || '—'} · Stock: {product.quantity}</p>
          <p className="price">{formatPrice(product.price)}</p>
          <p className="description">{product.description || 'No description.'}</p>
          <div className="product-detail-actions">
            <div className="product-detail-qty">
              <label htmlFor="qty">Quantity</label>
              <input
                id="qty"
                type="number"
                min={1}
                max={maxQty}
                value={quantity}
                onChange={(e) => setQuantity(Math.max(1, Math.min(maxQty, parseInt(e.target.value, 10) || 1)))}
              />
            </div>
            <div className="product-detail-buttons">
              <button type="button" className="btn btn-add-cart" onClick={handleAddToCart}>
                {addedToCart ? 'Added to cart' : 'Add to cart'}
              </button>
              <button type="button" className="btn btn-buy" onClick={handleBuy}>
                Buy
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
