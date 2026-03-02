import { useState, useEffect, useMemo } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { products } from '../api/client'
import { getMockProducts, categories, categorySubcategories } from '../data/mockProducts'
import ProductCard from '../components/ProductCard'
import './Home.css'

function filterByCategoryAndSub(list, categoryId, subName) {
  if (!list.length) return list
  const subTrim = (subName || '').trim()
  return list.filter((p) => {
    const catId = p.categoryId ?? (p.category && (categories.find((c) => c.name === p.category)?.id || categories.find((c) => c.id === p.category)?.id || p.category))
    if (categoryId && catId !== categoryId) return false
    if (subTrim) {
      const productSub = (p.subcategory || p.subCategory || '').trim()
      if (productSub !== subTrim) return false
    }
    return true
  })
}

export default function Home() {
  const { user } = useAuth()
  const [searchParams, setSearchParams] = useSearchParams()
  const categoryParam = searchParams.get('category') || ''
  const subParam = searchParams.get('sub') || ''

  const [list, setList] = useState([])
  const [loading, setLoading] = useState(true)
  const [filtersOpen, setFiltersOpen] = useState(false)
  const [useMock, setUseMock] = useState(false)

  useEffect(() => {
    products
      .getAll()
      .then((data) => {
        const items = Array.isArray(data) ? data : []
        if (items.length > 0) {
          setList(items)
          setUseMock(false)
          return
        }
        return getMockProducts().then((mock) => {
          setList(mock)
          setUseMock(true)
        })
      })
      .catch(() =>
        getMockProducts().then((mock) => {
          setList(mock)
          setUseMock(true)
        })
      )
      .finally(() => setLoading(false))
  }, [])

  const filteredList = useMemo(
    () => filterByCategoryAndSub(list, categoryParam || null, subParam || null),
    [list, categoryParam, subParam]
  )

  const listInCategory = useMemo(
    () => filterByCategoryAndSub(list, categoryParam || null, null),
    [list, categoryParam]
  )
  const subcategoryOptions = useMemo(() => {
    if (!categoryParam) return []
    const subs = categorySubcategories[categoryParam]
    if (!subs) return []
    const productSubs = new Set(
      listInCategory.map((p) => (p.subcategory || p.subCategory || '').trim()).filter(Boolean)
    )
    const options = []
    subs.forEach((s) => {
      if (s.name && productSubs.has(s.name)) options.push(s.name)
      if (s.children && s.children.length) {
        s.children.forEach((c) => {
          if (productSubs.has(c)) options.push(c)
        })
      }
    })
    return [...new Set(options)]
  }, [categoryParam, listInCategory])

  const setCategory = (value) => {
    const next = new URLSearchParams(searchParams)
    if (value) next.set('category', value)
    else next.delete('category')
    next.delete('sub')
    setSearchParams(next, { replace: true })
  }

  const setSubcategory = (value) => {
    const next = new URLSearchParams(searchParams)
    if (value) next.set('sub', value)
    else next.delete('sub')
    setSearchParams(next, { replace: true })
  }

  if (loading) {
    return (
      <div className="home">
        <div className="container">
          <div className="home-loading">Loading...</div>
        </div>
      </div>
    )
  }

  return (
    <div className="home">
      <div className="container">
        {user?.userType === 'admin' && (
          <div className="admin-hint">
            You're logged in as admin. To update product name, price, or add/remove products, go to <Link to="/admin">Admin</Link>.
          </div>
        )}
        <section className="filters-bar">
          <button
            type="button"
            className="filters-toggle"
            onClick={() => setFiltersOpen(!filtersOpen)}
            aria-expanded={filtersOpen}
          >
            <span className="filters-arrow">{filtersOpen ? '▲' : '▼'}</span>
            Show Filters
          </button>
          {(categoryParam || subParam) && (
            <span className="filter-active">
              {categoryParam && categories.find((c) => c.id === categoryParam)?.name}
              {subParam && ` → ${subParam}`}
              <button
                type="button"
                className="filter-clear"
                onClick={() => setSearchParams({}, { replace: true })}
                aria-label="Clear filters"
              >
                Clear
              </button>
            </span>
          )}
          {useMock && (
            <span className="mock-badge">Demo data — connect backend for live products</span>
          )}
        </section>

        {filtersOpen && (
          <div className="filters-panel">
            <div className="filter-group">
              <label htmlFor="filter-category">Category</label>
              <select
                id="filter-category"
                value={categoryParam}
                onChange={(e) => setCategory(e.target.value)}
              >
                <option value="">All categories</option>
                {categories.map((c) => (
                  <option key={c.id} value={c.id}>
                    {c.name}
                  </option>
                ))}
              </select>
            </div>
            {categoryParam && subcategoryOptions.length > 0 && (
              <div className="filter-group">
                <label htmlFor="filter-sub">Subcategory</label>
                <select
                  id="filter-sub"
                  value={subParam}
                  onChange={(e) => setSubcategory(e.target.value)}
                >
                  <option value="">All</option>
                  {subcategoryOptions.map((sub) => (
                    <option key={sub} value={sub}>
                      {sub}
                    </option>
                  ))}
                </select>
              </div>
            )}
          </div>
        )}

        <section id="products" className="products-section">
          {filteredList.length === 0 ? (
            <p className="no-products">
              {list.length === 0
                ? 'No products available.'
                : 'No products match the selected category or subcategory.'}
            </p>
          ) : (
            <div className="product-grid product-grid-unified">
              {filteredList.map((p, index) => (
                <ProductCard key={p.id} product={p} gradientIndex={index} />
              ))}
            </div>
          )}
        </section>
      </div>
    </div>
  )
}
