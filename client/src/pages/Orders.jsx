import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { orders } from '../api/client'

export default function Orders() {
  const [list, setList] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  useEffect(() => {
    orders.getMyOrders()
      .then(setList)
      .catch((e) => setError(e.message))
      .finally(() => setLoading(false))
  }, [])

  if (loading) return <div className="container">Loading orders...</div>
  if (error) return <div className="container alert-error">{error}</div>

  return (
    <div className="container">
      <h1>My Orders</h1>
      {list.length === 0 ? (
        <p>No orders yet. <Link to="/">Browse products</Link></p>
      ) : (
        <table style={{ width: '100%', borderCollapse: 'collapse', marginTop: '1rem', background: 'var(--color-surface)', borderRadius: 'var(--radius)', overflow: 'hidden', border: '1px solid var(--color-border)' }}>
          <thead>
            <tr style={{ background: 'var(--color-border)' }}>
              <th style={{ padding: '0.75rem', textAlign: 'left' }}>Order ID</th>
              <th style={{ padding: '0.75rem', textAlign: 'left' }}>Product</th>
              <th style={{ padding: '0.75rem', textAlign: 'right' }}>Qty</th>
              <th style={{ padding: '0.75rem', textAlign: 'right' }}>Total</th>
              <th style={{ padding: '0.75rem', textAlign: 'left' }}>Date</th>
            </tr>
          </thead>
          <tbody>
            {list.map((o) => (
              <tr key={o.id} style={{ borderTop: '1px solid var(--color-border)' }}>
                <td style={{ padding: '0.75rem' }}>{o.id}</td>
                <td style={{ padding: '0.75rem' }}>{o.productName}</td>
                <td style={{ padding: '0.75rem', textAlign: 'right' }}>{o.quantity}</td>
                <td style={{ padding: '0.75rem', textAlign: 'right' }}>${Number(o.totalPrice).toFixed(2)}</td>
                <td style={{ padding: '0.75rem', color: 'var(--color-text-muted)', fontSize: '0.875rem' }}>
                  {new Date(o.orderDate).toLocaleDateString()}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  )
}
