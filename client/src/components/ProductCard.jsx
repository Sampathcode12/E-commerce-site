import { Link } from 'react-router-dom'
import { getPlaceholderImageUrl } from '../data/mockProducts'
import { productImageUrl } from '../api/client'
import { formatPrice } from '../utils/formatPrice'
import './ProductCard.css'

const CARD_GRADIENTS = [
  'linear-gradient(135deg, #fefce8 0%, #fef9c3 100%)',
  'linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%)',
  'linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%)',
  'linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%)',
  'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)',
  'linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%)',
]

/**
 * Reusable product card: image, name, price (Rs.), optional Best Seller / New tags.
 * Use on Home and any page that lists products.
 */
export default function ProductCard({ product, gradientIndex = 0 }) {
  const firstPath = (product.imagePath || product.ImagePath || '').split(',')[0]?.trim()
  const imageUrl = (firstPath && productImageUrl(firstPath)) || getPlaceholderImageUrl(product.id, 400, 300)
  const style = { background: CARD_GRADIENTS[gradientIndex % CARD_GRADIENTS.length] }

  return (
    <Link
      to={`/product/${product.id}`}
      className="product-card product-card-unified"
      style={style}
    >
      <div className="product-card-image-wrap">
        <img
          src={imageUrl}
          alt={product.name}
          className="product-card-image"
          onError={(e) => {
            e.target.src = getPlaceholderImageUrl(product.id, 400, 300)
          }}
        />
        {product.bestSeller && (
          <span className="product-tag product-tag-bestseller">Best Seller</span>
        )}
        {product.isNew && (
          <span className="product-tag product-tag-new">New</span>
        )}
      </div>
      <div className="product-card-body">
        <h3 className="product-card-name">{product.name.toUpperCase()}</h3>
        <p className="product-card-price">{formatPrice(product.price)}</p>
      </div>
    </Link>
  )
}
