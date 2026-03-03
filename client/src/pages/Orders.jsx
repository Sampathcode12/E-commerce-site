import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { orders } from '../api/client'
import { useAuth } from '../context/AuthContext'
import { formatPrice } from '../utils/formatPrice'
import './Orders.css'

export default function Orders() {
  const { user } = useAuth()
  const isAdmin = user?.userType === 'admin'
  const [viewMode, setViewMode] = useState('mine') // 'mine' | 'all'
  const [list, setList] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  const loadOrders = () => {
    setLoading(true)
    setError(null)
    const promise = viewMode === 'all' && isAdmin ? orders.getAllOrders() : orders.getMyOrders()
    promise
      .then(setList)
      .catch((e) => setError(e.message))
      .finally(() => setLoading(false))
  }

  useEffect(() => {
    setList([])
    loadOrders()
  }, [viewMode, isAdmin])

  if (loading && list.length === 0) return <div className="container">Loading orders...</div>
  if (error && list.length === 0) return <div className="container alert-error">{error}</div>

  return (
    <div className="container">
      <div className="orders-page-header">
        <h1>{viewMode === 'all' ? 'All orders' : 'My orders'}</h1>
        {isAdmin && (
          <div className="orders-view-toggle">
            <button
              type="button"
              className={`btn btn-orders-tab ${viewMode === 'mine' ? 'active' : ''}`}
              onClick={() => setViewMode('mine')}
            >
              My orders
            </button>
            <button
              type="button"
              className={`btn btn-orders-tab ${viewMode === 'all' ? 'active' : ''}`}
              onClick={() => setViewMode('all')}
            >
              All orders (admin)
            </button>
          </div>
        )}
      </div>
      {error && <div className="alert-error orders-inline-error">{error}</div>}
      {list.length === 0 ? (
        <p>No orders yet. <Link to="/">Browse products</Link></p>
      ) : (
        <div className="orders-list">
          {list.map((order) => (
            <div key={order.id} className="order-card">
              <div className="order-header">
                <span>Order #{order.id}</span>
                {order.customerEmail && (
                  <span className="order-customer" title="Customer">({order.customerEmail})</span>
                )}
                <span className="order-date">{new Date(order.orderDate).toLocaleString()}</span>
                <span className="order-payment">{order.paymentMethod}</span>
              </div>
              <table className="order-items-table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th style={{ textAlign: 'right' }}>Qty</th>
                    <th style={{ textAlign: 'right' }}>Total</th>
                  </tr>
                </thead>
                <tbody>
                  {(order.items || []).map((item) => (
                    <tr key={item.sellId || item.productId}>
                      <td>{item.productName}</td>
                      <td style={{ textAlign: 'right' }}>{item.quantity}</td>
                      <td style={{ textAlign: 'right' }}>{formatPrice(item.totalPrice)}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
              <p className="order-total">Order total: <strong>{formatPrice(order.totalAmount)}</strong></p>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
