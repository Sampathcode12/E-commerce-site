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
  if (!res.ok) throw new Error(data.message || data.title || `Error ${res.status}`)
  return data
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
