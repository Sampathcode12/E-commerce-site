import { useAuth } from '../context/AuthContext'
import { Link } from 'react-router-dom'

export default function Account() {
  const { user } = useAuth()

  return (
    <div className="container">
      <h1>Account</h1>
      <div className="account-card" style={{ background: 'var(--color-surface)', padding: '1.5rem', borderRadius: 'var(--radius)', border: '1px solid var(--color-border)', maxWidth: '400px' }}>
        <p><strong>Email:</strong> {user?.email}</p>
        <p><strong>User type:</strong> {user?.userType || 'customer'}</p>
        <p style={{ marginTop: '1rem' }}>
          <Link to="/orders" className="btn">View my orders</Link>
        </p>
      </div>
    </div>
  )
}
