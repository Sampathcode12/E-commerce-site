// Temporary data for e-commerce home page (use until backend is ready)

/** Temporary placeholder image URL (Picsum Photos – stable per id, no API key) */
export function getPlaceholderImageUrl(id, width = 400, height = 300) {
  return `https://picsum.photos/seed/${encodeURIComponent(String(id))}/${width}/${height}`
}

export const categories = [
  { id: 'electronics', name: 'Electronics', icon: '📱', slug: 'electronics' },
  { id: 'fashion', name: 'Fashion', icon: '👕', slug: 'fashion' },
  { id: 'home', name: 'Home & Living', icon: '🏠', slug: 'home' },
  { id: 'sports', name: 'Sports & Outdoors', icon: '⚽', slug: 'sports' },
  { id: 'beauty', name: 'Beauty & Health', icon: '💄', slug: 'beauty' },
  { id: 'books', name: 'Books & Stationery', icon: '📚', slug: 'books' },
]

/** Subcategories per main category (Lassana-style mega menu) */
export const categorySubcategories = {
  electronics: [
    { name: 'Mobile Phones', children: ['Smartphones', 'Accessories', 'Cases'] },
    { name: 'Audio', children: ['Headphones', 'Earbuds', 'Speakers', 'Earphones'] },
    { name: 'Computers', children: ['Laptops', 'Desktops', 'Monitors', 'Keyboards'] },
    { name: 'Wearables', children: ['Smart Watches', 'Fitness Bands'] },
    { name: 'New Arrivals', children: [] },
  ],
  fashion: [
    { name: "Men's Wear", children: ['T-Shirts', 'Shirts', 'Pants', 'Jackets'] },
    { name: "Women's Wear", children: ['Dresses', 'Tops', 'Skirts', 'Jeans'] },
    { name: 'Bags & Accessories', children: ['Backpacks', 'Handbags', 'Wallets'] },
    { name: 'Footwear', children: ['Sneakers', 'Sandals', 'Formal Shoes'] },
    { name: 'New Arrivals', children: [] },
  ],
  home: [
    { name: 'Furniture', children: ['Living Room', 'Bedroom', 'Office'] },
    { name: 'Kitchen', children: ['Cookware', 'Storage', 'Appliances'] },
    { name: 'Decor', children: ['Lighting', 'Rugs', 'Wall Art'] },
    { name: 'Garden', children: ['Outdoor', 'Plants', 'Tools'] },
  ],
  sports: [
    { name: 'Fitness', children: ['Weights', 'Resistance Bands', 'Mats'] },
    { name: 'Outdoor', children: ['Camping', 'Hiking', 'Cycling'] },
    { name: 'Team Sports', children: ['Football', 'Cricket', 'Badminton'] },
    { name: 'Running', children: ['Shoes', 'Apparel', 'Accessories'] },
  ],
  beauty: [
    { name: 'Skincare', children: ['Creams', 'Serums', 'Cleansers', 'Masks'] },
    { name: 'Hair Care', children: ['Shampoo', 'Conditioner', 'Oils'] },
    { name: 'Makeup', children: ['Lipstick', 'Foundation', 'Eyes'] },
    { name: 'Personal Care', children: ['Oral Care', 'Body Care', 'Fragrance'] },
    { name: 'Health Supplements', children: ['Vitamins', 'Herbal', 'Wellness'] },
  ],
  books: [
    { name: 'Books', children: ['Fiction', 'Non-Fiction', 'Educational'] },
    { name: 'Stationery', children: ['Notebooks', 'Pens', 'Office Supplies'] },
    { name: 'Art & Craft', children: ['Drawing', 'Painting', 'DIY'] },
  ],
}

/* Temporary product data – with categoryId and subcategory for filtering */
export const mockProducts = [
  {
    id: 1,
    name: 'Dreaming About You',
    category: 'Electronics',
    categoryId: 'electronics',
    subcategory: 'Smartphones',
    price: 4350,
    description: 'Beautiful bouquet to show you care. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(1),
    quantity: 50,
    featured: true,
    bestSeller: true,
    isNew: false,
    rating: 4.5,
  },
  {
    id: 2,
    name: 'Dreamy Cloud',
    category: 'Electronics',
    categoryId: 'electronics',
    subcategory: 'Headphones',
    price: 5850,
    description: 'Soft and dreamy arrangement. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(2),
    quantity: 30,
    featured: true,
    bestSeller: true,
    isNew: false,
    rating: 4.8,
  },
  {
    id: 3,
    name: 'She Shines',
    category: 'Fashion',
    categoryId: 'fashion',
    subcategory: 'Dresses',
    price: 6850,
    description: 'Bright and radiant bouquet. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(3),
    quantity: 100,
    featured: true,
    bestSeller: false,
    isNew: true,
    rating: 4.6,
  },
  {
    id: 4,
    name: 'Bloom Her',
    category: 'Fashion',
    categoryId: 'fashion',
    subcategory: 'T-Shirts',
    price: 4450,
    description: 'Fresh blooms for every occasion. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(4),
    quantity: 200,
    featured: false,
    bestSeller: false,
    isNew: true,
    rating: 4.3,
  },
  {
    id: 5,
    name: 'Sweet Blossom',
    category: 'Home & Living',
    categoryId: 'home',
    subcategory: 'Living Room',
    price: 3200,
    description: 'Delicate and sweet arrangement. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(5),
    quantity: 150,
    featured: false,
    bestSeller: true,
    isNew: false,
    rating: 4.7,
  },
  {
    id: 6,
    name: 'Sunset Bouquet',
    category: 'Sports & Outdoors',
    categoryId: 'sports',
    subcategory: 'Running',
    price: 5100,
    description: 'Warm tones for evening gifting. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(6),
    quantity: 80,
    featured: true,
    bestSeller: true,
    isNew: false,
    rating: 4.4,
  },
  {
    id: 7,
    name: 'Morning Dew',
    category: 'Beauty & Health',
    categoryId: 'beauty',
    subcategory: 'Skincare',
    price: 3950,
    description: 'Fresh as morning. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(7),
    quantity: 60,
    featured: false,
    bestSeller: false,
    isNew: true,
    rating: 4.5,
  },
  {
    id: 8,
    name: 'Eternal Rose',
    category: 'Books & Stationery',
    categoryId: 'books',
    subcategory: 'Notebooks',
    price: 7600,
    description: 'Classic roses, long-lasting. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(8),
    quantity: 90,
    featured: false,
    bestSeller: true,
    isNew: false,
    rating: 4.6,
  },
  {
    id: 9,
    name: 'Garden Joy',
    category: 'Home & Living',
    categoryId: 'home',
    subcategory: 'Garden',
    price: 4200,
    description: 'A mix of garden favourites. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(9),
    quantity: 120,
    featured: false,
    bestSeller: false,
    isNew: true,
    rating: 4.4,
  },
  {
    id: 10,
    name: 'Pure White',
    category: 'Electronics',
    categoryId: 'electronics',
    subcategory: 'Laptops',
    price: 5500,
    description: 'Elegant white bouquet. Temporary placeholder product.',
    imagePath: getPlaceholderImageUrl(10),
    quantity: 300,
    featured: false,
    bestSeller: true,
    isNew: false,
    rating: 4.2,
  },
]

export function getMockProducts() {
  return Promise.resolve([...mockProducts])
}

export function getMockProductById(id) {
  const numId = Number(id)
  const product = mockProducts.find((p) => p.id === numId)
  return product ? Promise.resolve({ ...product }) : Promise.reject(new Error('Product not found'))
}

export function getFeaturedProducts() {
  return mockProducts.filter((p) => p.featured)
}
