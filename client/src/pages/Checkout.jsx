import { useState, useEffect } from 'react'
import { useParams, useNavigate, Link } from 'react-router-dom'
import { products, orders } from '../api/client'
import { useCart } from '../context/CartContext'
import { getPlaceholderImageUrl } from '../data/mockProducts'
import { formatPrice } from '../utils/formatPrice'
import './Checkout.css'

export default function Checkout() {
  const { id } = useParams()
  const navigate = useNavigate()
  const { items: cartItems, cartTotal, clearCart } = useCart()
  const [product, setProduct] = useState(null)
  const [quantity, setQuantity] = useState(1)
  const [paymentMethod, setPaymentMethod] = useState('credit_card')
  const [loading, setLoading] = useState(!!id)
  const [submitting, setSubmitting] = useState(false)
  const [error, setError] = useState(null)

  const isCartCheckout = !id

  useEffect(() => {
    if (!id) {
      setLoading(false)
      return
    }
    products.getById(id)
      .then(setProduct)
      .catch((e) => setError(e.message))
      .finally(() => setLoading(false))
  }, [id])

  const handleSubmitSingle = async (e) => {
    e.preventDefault()
    if (!product) return
    const q = Math.max(1, Math.min(quantity, product.quantity))
    setSubmitting(true)
    setError(null)
    try {
      await orders.placeOrder({
        productId: product.id,
        quantity: q,
        paymentMethod,
      })
      navigate('/orders')
    } catch (err) {
      setError(err.message || 'Order failed')
    } finally {
      setSubmitting(false)
    }
  }

  const handleSubmitCart = async (e) => {
    e.preventDefault()
    if (!cartItems.length) return
    setSubmitting(true)
    setError(null)
    try {
      for (const item of cartItems) {
        await orders.placeOrder({
          productId: item.productId,
          quantity: item.orderQuantity || 1,
          paymentMethod,
        })
      }
      clearCart()
      navigate('/orders')
    } catch (err) {
      setError(err.message || 'Order failed')
    } finally {
      setSubmitting(false)
    }
  }

  if (loading) return <div className="container">Loading...</div>

  if (isCartCheckout) {
    if (cartItems.length === 0) {
      return (
        <div className="container checkout-page">
          <h1>Checkout</h1>
          <p className="checkout-empty">Your cart is empty. <Link to="/">Continue shopping</Link>.</p>
        </div>
      )
    }
    return (
      <div className="container checkout-page">
        <h1>Checkout</h1>
        <div className="checkout-card">
          <div className="checkout-items">
            {cartItems.map((item) => (
              <div key={item.productId} className="checkout-product checkout-product-row">
                <img
                  src={item.imagePath || getPlaceholderImageUrl(item.productId, 80, 80)}
                  alt={item.name}
                  onError={(e) => {
                    e.target.src = getPlaceholderImageUrl(item.productId, 80, 80)
                  }}
                />
                <div>
                  <h3>{item.name}</h3>
                  <p>{formatPrice(item.price)} × {item.orderQuantity}</p>
                </div>
                <div className="checkout-line-total">{formatPrice((item.price || 0) * (item.orderQuantity || 1))}</div>
              </div>
            ))}
          </div>
          <form onSubmit={handleSubmitCart} className="checkout-form">
            {error && <div className="alert-error">{error}</div>}
            <div className="form-group">
              <label>Payment method</label>
              <select value={paymentMethod} onChange={(e) => setPaymentMethod(e.target.value)}>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="bank_transfer">Bank Transfer</option>
              </select>
            </div>
            <p className="total">Total: <strong>{formatPrice(cartTotal)}</strong></p>
            <button type="submit" className="btn" disabled={submitting}>
              {submitting ? 'Placing order...' : 'Place order'}
            </button>
          </form>
        </div>
      </div>
    )
  }

  if (error && !product) return <div className="container alert-error">{error}</div>
  if (!product) return null

  const maxQty = product.quantity
  const total = (quantity <= maxQty ? quantity : maxQty) * Number(product.price)

  return (
    <div className="container checkout-page">
      <h1>Checkout</h1>
      <div className="checkout-card">
        <div className="checkout-product">
          <img
            src={product.imagePath || getPlaceholderImageUrl(product.id, 240, 180)}
            alt={product.name}
            onError={(e) => {
              e.target.src = getPlaceholderImageUrl(product.id, 240, 180)
            }}
          />
          <div>
            <h3>{product.name}</h3>
            <p>{formatPrice(product.price)} each · Stock: {product.quantity}</p>
          </div>
        </div>
        <form onSubmit={handleSubmitSingle} className="checkout-form">
          {error && <div className="alert-error">{error}</div>}
          <div className="form-group">
            <label>Quantity</label>
            <input
              type="number"
              min={1}
              max={maxQty}
              value={quantity}
              onChange={(e) => setQuantity(parseInt(e.target.value, 10) || 1)}
            />
          </div>
          <div className="form-group">
            <label>Payment method</label>
            <select value={paymentMethod} onChange={(e) => setPaymentMethod(e.target.value)}>
              <option value="credit_card">Credit Card</option>
              <option value="paypal">PayPal</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
          </div>
          <p className="total">Total: <strong>{formatPrice(total)}</strong></p>
          <button type="submit" className="btn" disabled={submitting}>
            {submitting ? 'Placing order...' : 'Place order'}
          </button>
        </form>
      </div>
    </div>
  )
}
