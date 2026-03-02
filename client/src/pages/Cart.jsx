import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { useCart } from '../context/CartContext'
import { getPlaceholderImageUrl } from '../data/mockProducts'
import { formatPrice } from '../utils/formatPrice'
import './Cart.css'

export default function Cart() {
  const navigate = useNavigate()
  const { token } = useAuth()
  const { items, setItemQuantity, removeItem, cartTotal } = useCart()

  const handleCheckout = () => {
    if (!token) {
      navigate('/login')
      return
    }
    navigate('/checkout')
  }

  if (items.length === 0) {
    return (
      <div className="container cart-page">
        <h1>Cart</h1>
        <div className="cart-empty">
          <p>Your cart is empty.</p>
          <Link to="/" className="btn btn-primary">Continue shopping</Link>
        </div>
      </div>
    )
  }

  return (
    <div className="container cart-page">
      <h1>Cart</h1>
      <div className="cart-list">
        {items.map((item) => {
          const imgSrc = item.imagePath || getPlaceholderImageUrl(item.productId, 120, 120)
          const lineTotal = (item.price || 0) * (item.orderQuantity || 1)
          const maxQty = item.quantity ?? 999
          return (
            <div key={item.productId} className="cart-item">
              <Link to={`/product/${item.productId}`} className="cart-item-image-wrap">
                <img
                  src={imgSrc}
                  alt={item.name}
                  onError={(e) => {
                    e.target.src = getPlaceholderImageUrl(item.productId, 120, 120)
                  }}
                />
              </Link>
              <div className="cart-item-details">
                <Link to={`/product/${item.productId}`} className="cart-item-name">
                  {item.name}
                </Link>
                <p className="cart-item-price">{formatPrice(item.price)} each</p>
                <div className="cart-item-actions">
                  <label className="cart-item-qty-label">
                    Qty
                    <input
                      type="number"
                      min={1}
                      max={maxQty}
                      value={item.orderQuantity}
                      onChange={(e) => {
                        const v = parseInt(e.target.value, 10)
                        if (!Number.isNaN(v)) setItemQuantity(item.productId, v)
                      }}
                    />
                  </label>
                  <button
                    type="button"
                    className="cart-item-remove"
                    onClick={() => removeItem(item.productId)}
                    aria-label="Remove"
                  >
                    Remove
                  </button>
                </div>
              </div>
              <div className="cart-item-total">
                {formatPrice(lineTotal)}
              </div>
            </div>
          )
        })}
      </div>
      <div className="cart-footer">
        <p className="cart-grand-total">
          Total: <strong>{formatPrice(cartTotal)}</strong>
        </p>
        <button type="button" className="btn btn-checkout" onClick={handleCheckout}>
          Proceed to checkout
        </button>
      </div>
    </div>
  )
}
