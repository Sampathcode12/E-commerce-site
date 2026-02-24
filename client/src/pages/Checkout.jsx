import { useState, useEffect } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { products, orders } from '../api/client'
import './Checkout.css'

export default function Checkout() {
  const { id } = useParams()
  const navigate = useNavigate()
  const [product, setProduct] = useState(null)
  const [quantity, setQuantity] = useState(1)
  const [paymentMethod, setPaymentMethod] = useState('credit_card')
  const [loading, setLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)
  const [error, setError] = useState(null)

  useEffect(() => {
    products.getById(id)
      .then(setProduct)
      .catch((e) => setError(e.message))
      .finally(() => setLoading(false))
  }, [id])

  const handleSubmit = async (e) => {
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

  if (loading) return <div className="container">Loading...</div>
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
            src={product.imagePath || 'https://via.placeholder.com/120x90?text=No+Image'}
            alt={product.name}
          />
          <div>
            <h3>{product.name}</h3>
            <p>${Number(product.price).toFixed(2)} each · Stock: {product.quantity}</p>
          </div>
        </div>
        <form onSubmit={handleSubmit} className="checkout-form">
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
          <p className="total">Total: <strong>${total.toFixed(2)}</strong></p>
          <button type="submit" className="btn" disabled={submitting}>
            {submitting ? 'Placing order...' : 'Place order'}
          </button>
        </form>
      </div>
    </div>
  )
}
