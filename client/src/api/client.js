const API_BASE = import.meta.env.VITE_API_URL || 'http://localhost:5000/api'
const API_ORIGIN = (import.meta.env.VITE_API_URL || 'http://localhost:5000/api').replace(/\/api\/?$/, '')

function getToken() {
  return localStorage.getItem('ecommerce_token')
}

/** Resolve product image URL (handles /uploads/... from same origin as API) */
export function productImageUrl(path) {
  if (!path) return null
  if (path.startsWith('http')) return path
  return `${API_ORIGIN}${path.startsWith('/') ? path : `/${path}`}`
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
  if (status === 401)
    return 'Session expired or you don’t have permission. Please log out and log in again as admin, then try again.'
  if (status === 403)
    return 'You don’t have permission to do this. Please log out and log in again as admin (admin@example.com), then try again.'
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
  update: (id, body) => api(`/products/${id}`, { method: 'PUT', body: JSON.stringify(body) }),
  delete: (id) => api(`/products/${id}`, { method: 'DELETE' }).then(() => ({})),
}

export const orders = {
  getMyOrders: () => api('/orders'),
  placeOrder: (body) => api('/orders', { method: 'POST', body: JSON.stringify(body) }),
}

export async function uploadImages(files) {
  if (!files?.length) return []
  const form = new FormData()
  for (let i = 0; i < files.length; i++) form.append('files', files[i])
  const token = getToken()
  const res = await fetch(`${API_BASE}/upload`, {
    method: 'POST',
    headers: token ? { Authorization: `Bearer ${token}` } : {},
    body: form,
  })
  const data = await res.json().catch(() => ({}))
  if (!res.ok) throw new Error(data.message || `Upload failed: ${res.status}`)
  return data.urls || []
}
