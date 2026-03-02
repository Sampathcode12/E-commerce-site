import { useState, useEffect } from 'react'
import { products, uploadImages, productImageUrl } from '../api/client'
import { categories, categorySubcategories } from '../data/mockProducts'
import { formatPrice } from '../utils/formatPrice'
import './Admin.css'

function flattenSubcategories(categoryId) {
  const subs = categorySubcategories[categoryId]
  if (!subs) return []
  const out = []
  subs.forEach((s) => {
    if (s.name) out.push(s.name)
    if (s.children?.length) s.children.forEach((c) => out.push(c))
  })
  return [...new Set(out)]
}

export default function Admin() {
  const [list, setList] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const [adding, setAdding] = useState(false)
  const [editingId, setEditingId] = useState(null)
  const [form, setForm] = useState({
    name: '',
    productId: '',
    price: '',
    quantity: 10,
    category: '',
    subCategory: '',
    description: '',
    imagePath: '',
  })
  const [pendingImageFiles, setPendingImageFiles] = useState([])
  const [pendingImageUrls, setPendingImageUrls] = useState([])
  const [uploading, setUploading] = useState(false)

  useEffect(() => {
    const urls = pendingImageFiles.map((f) => URL.createObjectURL(f))
    setPendingImageUrls(urls)
    return () => urls.forEach((u) => URL.revokeObjectURL(u))
  }, [pendingImageFiles])

  const loadProducts = () => {
    setError(null)
    products
      .getAll()
      .then((data) => setList(Array.isArray(data) ? data : []))
      .catch((e) => setError(e.message || 'Cannot load products. Start the backend (run dotnet run in the backend folder) to update product details.'))
      .finally(() => setLoading(false))
  }

  useEffect(() => {
    loadProducts()
  }, [])

  const resetForm = () => {
    setForm({
      name: '',
      productId: '',
      price: '',
      quantity: 10,
      category: '',
      subCategory: '',
      description: '',
      imagePath: '',
    })
    setPendingImageFiles([])
    setAdding(false)
    setEditingId(null)
  }

  const existingImageUrls = (form.imagePath || '').split(',').map((s) => s.trim()).filter(Boolean)

  const handleAdd = async (e) => {
    e.preventDefault()
    const name = form.name.trim()
    const productId = (form.productId || form.name.replace(/\s+/g, '-').toLowerCase()).trim() || `prod-${Date.now()}`
    const price = Number(form.price)
    const quantity = Math.max(0, parseInt(form.quantity, 10) || 0)
    const category = (form.category || '').trim() || 'General'
    const subCategory = (form.subCategory || '').trim() || ''
    if (!name || Number.isNaN(price) || price < 0) {
      setError('Name and a valid price are required.')
      return
    }
    setError(null)
    let imagePath = (form.imagePath || '').trim()
    if (pendingImageFiles.length > 0) {
      setUploading(true)
      try {
        const urls = await uploadImages(pendingImageFiles)
        imagePath = [...(imagePath ? imagePath.split(',') : []), ...urls].filter(Boolean).join(',')
      } catch (err) {
        setUploading(false)
        const msg = err.message || 'Image upload failed.'
        const is404 = /404/.test(msg)
        setError(
          is404
            ? 'Image upload endpoint not found (404). Make sure the backend is running (dotnet run in backend folder) and rebuilt. Adding product without images.'
            : msg + ' Adding product without images.'
        )
        imagePath = (form.imagePath || '').trim()
      }
      setUploading(false)
    }
    products
      .create({
        name,
        productId,
        quantity,
        category,
        subCategory,
        price,
        description: form.description.trim() || null,
        imagePath: imagePath || undefined,
      })
      .then(() => {
        setError(null)
        resetForm()
        loadProducts()
      })
      .catch((e) => setError(e.message))
  }

  const handleUpdate = async (e) => {
    e.preventDefault()
    const id = editingId
    if (!id) return
    const name = form.name.trim()
    const price = Number(form.price)
    const quantity = Math.max(0, parseInt(form.quantity, 10) || 0)
    const category = (form.category || '').trim()
    const subCategory = (form.subCategory || '').trim()
    if (!name || Number.isNaN(price) || price < 0) {
      setError('Name and a valid price are required.')
      return
    }
    setError(null)
    let imagePath = (form.imagePath || '').trim()
    if (pendingImageFiles.length > 0) {
      setUploading(true)
      try {
        const urls = await uploadImages(pendingImageFiles)
        imagePath = [...(imagePath ? imagePath.split(',') : []), ...urls].filter(Boolean).join(',')
      } catch (err) {
        setError(err.message || 'Image upload failed.')
        setUploading(false)
        return
      }
      setUploading(false)
    }
    products
      .update(id, { name, price, quantity, category: category || undefined, subCategory: subCategory || undefined, description: form.description.trim() || undefined, imagePath: imagePath || undefined })
      .then(() => {
        resetForm()
        loadProducts()
      })
      .catch((e) => setError(e.message))
  }

  const handleDelete = (product) => {
    if (!window.confirm(`Remove "${product.name || product.Name}"?`)) return
    setError(null)
    products
      .delete(product.id ?? product.Id)
      .then(() => loadProducts())
      .catch((e) => setError(e.message))
  }

  const startEdit = (p) => {
    setEditingId(p.id ?? p.Id)
    const catName = p.category ?? p.Category ?? ''
    const catMatch = categories.find((c) => c.name === catName || c.id === catName)
    setForm({
      name: p.name ?? p.Name ?? '',
      productId: p.productId ?? p.ProductId ?? '',
      price: String(p.price ?? p.Price ?? ''),
      quantity: p.quantity ?? p.Quantity ?? 0,
      category: catMatch?.id ?? catName ?? '',
      subCategory: p.subCategory ?? p.SubCategory ?? '',
      description: p.description ?? p.Description ?? '',
      imagePath: p.imagePath ?? p.ImagePath ?? '',
    })
    setPendingImageFiles([])
    setAdding(false)
  }

  const subOptions = form.category ? flattenSubcategories(form.category) : []

  if (loading) {
    return (
      <div className="container admin-page">
        <p>Loading products...</p>
      </div>
    )
  }

  return (
    <div className="container admin-page">
      <h1>Admin – Update product details</h1>
      <p className="admin-intro">Add new products, update name and price, or remove products. Changes are saved to the backend.</p>
      {error && (
        <div className="admin-error" role="alert">
          {error}
          <button type="button" className="admin-error-dismiss" onClick={() => setError(null)} aria-label="Dismiss">×</button>
        </div>
      )}

      <section className="admin-actions">
        {!adding && !editingId && (
          <button type="button" className="btn btn-primary" onClick={() => { setAdding(true); setError(null); setForm({ name: '', productId: '', price: '', quantity: 10, category: '', subCategory: '', description: '', imagePath: '' }); setPendingImageFiles([]); }}>
            Add product
          </button>
        )}
      </section>

      {(adding || editingId) && (
        <form className="admin-form" onSubmit={adding ? handleAdd : handleUpdate}>
          <h2>{editingId ? 'Edit product' : 'New product'}</h2>
          <div className="admin-form-grid">
            <div className="form-group">
              <label htmlFor="admin-name">Name *</label>
              <input id="admin-name" value={form.name} onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))} required />
            </div>
            {adding && (
              <div className="form-group">
                <label htmlFor="admin-productId">Product ID (optional)</label>
                <input id="admin-productId" value={form.productId} onChange={(e) => setForm((f) => ({ ...f, productId: e.target.value }))} placeholder="e.g. prod-101" />
              </div>
            )}
            <div className="form-group">
              <label htmlFor="admin-price">Price (Rs.) *</label>
              <input id="admin-price" type="number" step="0.01" min="0" value={form.price} onChange={(e) => setForm((f) => ({ ...f, price: e.target.value }))} required />
            </div>
            <div className="form-group">
              <label htmlFor="admin-quantity">Quantity</label>
              <input id="admin-quantity" type="number" min="0" value={form.quantity} onChange={(e) => setForm((f) => ({ ...f, quantity: e.target.value }))} />
            </div>
            <div className="form-group">
              <label htmlFor="admin-category">Category</label>
              <select id="admin-category" value={form.category} onChange={(e) => setForm((f) => ({ ...f, category: e.target.value, subCategory: '' }))}>
                <option value="">—</option>
                {categories.map((c) => (
                  <option key={c.id} value={c.id}>{c.name}</option>
                ))}
              </select>
            </div>
            {subOptions.length > 0 && (
              <div className="form-group">
                <label htmlFor="admin-sub">Subcategory</label>
                <select id="admin-sub" value={form.subCategory} onChange={(e) => setForm((f) => ({ ...f, subCategory: e.target.value }))}>
                  <option value="">—</option>
                  {subOptions.map((s) => (
                    <option key={s} value={s}>{s}</option>
                  ))}
                </select>
              </div>
            )}
            <div className="form-group admin-form-desc">
              <label htmlFor="admin-desc">Description</label>
              <textarea id="admin-desc" rows={2} value={form.description} onChange={(e) => setForm((f) => ({ ...f, description: e.target.value }))} />
            </div>
            <div className="form-group admin-form-images">
              <label>Product images (multiple)</label>
              <input
                id="admin-images"
                type="file"
                accept="image/jpeg,image/png,image/gif,image/webp"
                multiple
                onChange={(e) => setPendingImageFiles((prev) => [...prev, ...(e.target.files ? Array.from(e.target.files) : [])])}
              />
              <div className="admin-image-previews">
                {existingImageUrls.map((url) => (
                  <div key={url} className="admin-preview-wrap">
                    <img src={productImageUrl(url)} alt="" />
                    <button type="button" className="admin-preview-remove" onClick={() => setForm((f) => ({ ...f, imagePath: (f.imagePath || '').split(',').map((s) => s.trim()).filter((s) => s !== url).join(',') }))} aria-label="Remove">×</button>
                  </div>
                ))}
                {pendingImageFiles.map((file, i) => (
                  <div key={`pending-${i}`} className="admin-preview-wrap">
                    <img src={pendingImageUrls[i]} alt="" />
                    <button type="button" className="admin-preview-remove" onClick={() => setPendingImageFiles((prev) => prev.filter((_, j) => j !== i))} aria-label="Remove">×</button>
                  </div>
                ))}
              </div>
            </div>
          </div>
          <div className="admin-form-buttons">
            <button type="submit" className="btn btn-primary" disabled={uploading}>{uploading ? 'Uploading...' : (editingId ? 'Save' : 'Add product')}</button>
            <button type="button" className="btn btn-secondary" onClick={resetForm}>Cancel</button>
          </div>
        </form>
      )}

      <section className="admin-table-wrap">
        <h2>Products ({list.length})</h2>
        {list.length === 0 ? (
          <p className="admin-empty">No products in the database. Add one above or start the backend and run migrations.</p>
        ) : (
          <table className="admin-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {list.map((p) => {
                const id = p.id ?? p.Id
                const name = p.name ?? p.Name
                const price = p.price ?? p.Price
                const quantity = p.quantity ?? p.Quantity
                const category = p.category ?? p.Category
                const subCategory = p.subCategory ?? p.SubCategory
                return (
                  <tr key={id}>
                    <td>{name}</td>
                    <td>{formatPrice(price)}</td>
                    <td>{quantity}</td>
                    <td>{category ?? '—'}</td>
                    <td>{subCategory ?? '—'}</td>
                    <td>
                      <button type="button" className="btn btn-sm btn-edit" onClick={() => startEdit(p)}>Edit</button>
                      <button type="button" className="btn btn-sm btn-danger" onClick={() => handleDelete(p)}>Remove</button>
                    </td>
                  </tr>
                )
              })}
            </tbody>
          </table>
        )}
      </section>
    </div>
  )
}
