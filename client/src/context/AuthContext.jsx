import { createContext, useContext, useState, useCallback, useEffect } from 'react'

const AuthContext = createContext(null)

const TOKEN_KEY = 'ecommerce_token'
const USER_KEY = 'ecommerce_user'

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null)
  const [token, setTokenState] = useState(() => localStorage.getItem(TOKEN_KEY))
  const [loading, setLoading] = useState(true)

  const setToken = useCallback((newToken, userData) => {
    if (newToken && userData) {
      localStorage.setItem(TOKEN_KEY, newToken)
      localStorage.setItem(USER_KEY, JSON.stringify(userData))
      setTokenState(newToken)
      setUser(userData)
    } else {
      localStorage.removeItem(TOKEN_KEY)
      localStorage.removeItem(USER_KEY)
      setTokenState(null)
      setUser(null)
    }
  }, [])

  useEffect(() => {
    const stored = localStorage.getItem(USER_KEY)
    if (stored) {
      try {
        setUser(JSON.parse(stored))
      } catch (_) {
        setToken(null, null)
      }
    }
    setLoading(false)
  }, [setToken])

  const logout = useCallback(() => {
    setToken(null, null)
  }, [setToken])

  return (
    <AuthContext.Provider value={{ user, token, setToken, logout, loading }}>
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  const ctx = useContext(AuthContext)
  if (!ctx) throw new Error('useAuth must be used within AuthProvider')
  return ctx
}
