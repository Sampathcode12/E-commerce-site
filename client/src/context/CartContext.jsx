import { createContext, useContext, useState, useCallback, useEffect } from 'react'

const CartContext = createContext(null)
const CART_KEY = 'ecommerce_cart'

function loadCart() {
  try {
    const raw = localStorage.getItem(CART_KEY)
    if (!raw) return []
    const data = JSON.parse(raw)
    return Array.isArray(data) ? data : []
  } catch {
    return []
  }
}

function saveCart(items) {
  try {
    localStorage.setItem(CART_KEY, JSON.stringify(items))
  } catch (_) {}
}

export function CartProvider({ children }) {
  const [items, setItems] = useState(loadCart)

  useEffect(() => {
    saveCart(items)
  }, [items])

  const addItem = useCallback((product, quantity = 1) => {
    const stock = product.quantity ?? 999
    const qty = Math.max(1, Math.min(Number(quantity) || 1, stock))
    setItems((prev) => {
      const i = prev.findIndex((x) => x.productId === product.id)
      const snap = {
        productId: product.id,
        name: product.name,
        price: Number(product.price),
        imagePath: product.imagePath || null,
        quantity: stock,
      }
      if (i >= 0) {
        const next = [...prev]
        const newQty = Math.min((next[i].orderQuantity || 1) + qty, next[i].quantity)
        next[i] = { ...next[i], orderQuantity: newQty }
        return next
      }
      return [...prev, { ...snap, orderQuantity: qty }]
    })
  }, [])

  const setItemQuantity = useCallback((productId, orderQuantity) => {
    const qty = Math.max(1, Number(orderQuantity) || 1)
    setItems((prev) =>
      prev.map((x) =>
        x.productId === productId ? { ...x, orderQuantity: Math.min(qty, x.quantity) } : x
      )
    )
  }, [])

  const removeItem = useCallback((productId) => {
    setItems((prev) => prev.filter((x) => x.productId !== productId))
  }, [])

  const clearCart = useCallback(() => setItems([]), [])

  const cartCount = items.reduce((sum, x) => sum + (x.orderQuantity || 1), 0)
  const cartTotal = items.reduce((sum, x) => sum + (x.price || 0) * (x.orderQuantity || 1), 0)

  const value = {
    items,
    addItem,
    setItemQuantity,
    removeItem,
    clearCart,
    cartCount,
    cartTotal,
  }

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>
}

export function useCart() {
  const ctx = useContext(CartContext)
  if (!ctx) throw new Error('useCart must be used within CartProvider')
  return ctx
}
