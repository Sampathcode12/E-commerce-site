import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { auth } from '../api/client'
import './Register.css'

export default function Signup() {
  const navigate = useNavigate()
  const { setToken } = useAuth()
  const [form, setForm] = useState({
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    phone: '',
    address: '',
    age: '',
    sex: 'Male',
    interests: '',
    bankAccountNumber: '',
    bankName: '',
  })
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)

  const handleChange = (e) => {
    const { name, value } = e.target
    setForm((prev) => ({ ...prev, [name]: value }))
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError('')
    setLoading(true)
    try {
      const body = {
        ...form,
        age: form.age ? parseInt(form.age, 10) : null,
      }
      const res = await auth.register(body)
      setToken(res.token, { userId: res.userId, email: res.email, userType: res.userType })
      navigate('/')
    } catch (err) {
      setError(err.message || 'Registration failed')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="register-page">
      <div className="register-card">
        <h1>Create account</h1>
        <p className="register-subtitle">Sign up to get started</p>
        {error && <div className="alert-error">{error}</div>}
        <form onSubmit={handleSubmit} className="register-form">
          <div className="form-row">
            <div className="form-group">
              <label htmlFor="firstName">First name</label>
              <input id="firstName" name="firstName" value={form.firstName} onChange={handleChange} required />
            </div>
            <div className="form-group">
              <label htmlFor="lastName">Last name</label>
              <input id="lastName" name="lastName" value={form.lastName} onChange={handleChange} required />
            </div>
          </div>
          <div className="form-group">
            <label htmlFor="email">Email</label>
            <input id="email" name="email" type="email" value={form.email} onChange={handleChange} required />
          </div>
          <div className="form-group">
            <label htmlFor="password">Password</label>
            <input id="password" name="password" type="password" value={form.password} onChange={handleChange} required />
          </div>
          <div className="form-group">
            <label htmlFor="phone">Phone</label>
            <input id="phone" name="phone" value={form.phone} onChange={handleChange} />
          </div>
          <div className="form-group">
            <label htmlFor="address">Address</label>
            <textarea id="address" name="address" rows={2} value={form.address} onChange={handleChange} />
          </div>
          <div className="form-row">
            <div className="form-group">
              <label htmlFor="age">Age</label>
              <input id="age" name="age" type="number" min="1" value={form.age} onChange={handleChange} />
            </div>
            <div className="form-group">
              <label htmlFor="sex">Sex</label>
              <select id="sex" name="sex" value={form.sex} onChange={handleChange}>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
          <div className="form-group">
            <label htmlFor="interests">Interests</label>
            <textarea id="interests" name="interests" rows={2} value={form.interests} onChange={handleChange} />
          </div>
          <div className="form-group">
            <label htmlFor="bankAccountNumber">Bank account (optional)</label>
            <input id="bankAccountNumber" name="bankAccountNumber" value={form.bankAccountNumber} onChange={handleChange} />
          </div>
          <div className="form-group">
            <label htmlFor="bankName">Bank name (optional)</label>
            <input id="bankName" name="bankName" value={form.bankName} onChange={handleChange} />
          </div>
          <button type="submit" className="btn-register" disabled={loading}>
            {loading ? 'Creating account...' : 'Sign up'}
          </button>
        </form>
        <p className="register-footer">
          Already have an account? <Link to="/login">Sign in</Link>
        </p>
      </div>
    </div>
  )
}
