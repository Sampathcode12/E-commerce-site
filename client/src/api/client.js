const API_BASE = import.meta.env.VITE_API_URL || 'http://localhost:5000/api'

function getToken() {
  return localStorage.getItem('ecommerce_token')
}

export async function api(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`
  const headers = {
    'Content-Type': 'application/json',
    ...options.headers,
  }
  const token = getToken()
  if (token) headers['Authorization'] = `Bearer ${token}`

  const res = await fetch(url, { ...options, headers })
  const data = await res.json().catch(() => ({}))
  if (!res.ok) {
    const message = getErrorMessage(data, res.status)
    throw new Error(message)
  }
  return data
}

function getErrorMessage(data, status) {
  if (data.message) return data.message
  if (data.errors && typeof data.errors === 'object') {
    const parts = []
    for (const [field, messages] of Object.entries(data.errors)) {
      const list = Array.isArray(messages) ? messages : [messages]
      const label = field.replace(/([A-Z])/g, ' $1').replace(/^./, (s) => s.toUpperCase()).trim()
      parts.push(`${label}: ${list.join(', ')}`)
    }
    if (parts.length) return parts.join('. ')
  }
  if (data.title && data.title !== 'One or more validation errors occurred') return data.title
  if (data.detail) return data.detail
  return `Error ${status}`
}

export const auth = {
  login: (email, password) =>
    api('/auth/login', { method: 'POST', body: JSON.stringify({ email, password }) }),
  register: (body) =>
    api('/auth/register', { method: 'POST', body: JSON.stringify(body) }),
}

export const products = {
  getAll: () => api('/products'),
  getById: (id) => api(`/products/${id}`),
  create: (body) => api('/products', { method: 'POST', body: JSON.stringify(body) }),
}

export const orders = {
  getMyOrders: () => api('/orders'),
  placeOrder: (body) => api('/orders', { method: 'POST', body: JSON.stringify(body) }),
}
