// Temporary data for e-commerce home page (use until backend is ready)

const PLACEHOLDER_IMG = 'https://images.unsplash.com/placeholder'

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

export const mockProducts = [
  {
    id: 1,
    name: 'Wireless Bluetooth Headphones',
    category: 'Electronics',
    price: 49.99,
    description: 'Premium sound quality, 20hr battery, comfortable over-ear design.',
    imagePath: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop',
    quantity: 50,
    featured: true,
    bestSeller: true,
    rating: 4.5,
  },
  {
    id: 2,
    name: 'Smart Watch Pro',
    category: 'Electronics',
    price: 129.99,
    description: 'Heart rate, GPS, 50m water resistant. Works with iOS & Android.',
    imagePath: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=300&fit=crop',
    quantity: 30,
    featured: true,
    bestSeller: false,
    rating: 4.8,
  },
  {
    id: 3,
    name: 'Running Shoes - Men',
    category: 'Sports & Outdoors',
    price: 89.99,
    description: 'Lightweight, breathable mesh. Perfect for daily runs.',
    imagePath: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=300&fit=crop',
    quantity: 100,
    featured: true,
    bestSeller: true,
    rating: 4.6,
  },
  {
    id: 4,
    name: 'Cotton T-Shirt Pack (3)',
    category: 'Fashion',
    price: 24.99,
    description: '100% cotton, slim fit. Assorted colors.',
    imagePath: 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=400&h=300&fit=crop',
    quantity: 200,
    featured: false,
    bestSeller: true,
    rating: 4.3,
  },
  {
    id: 5,
    name: 'Stainless Steel Water Bottle',
    category: 'Home & Living',
    price: 19.99,
    description: '1L capacity, BPA-free, keeps cold 24hr / hot 12hr.',
    imagePath: 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=400&h=300&fit=crop',
    quantity: 150,
    featured: false,
    rating: 4.7,
  },
  {
    id: 6,
    name: 'LED Desk Lamp',
    category: 'Electronics',
    price: 34.99,
    description: 'Adjustable brightness, USB charging port, modern design.',
    imagePath: 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=400&h=300&fit=crop',
    quantity: 80,
    featured: true,
    bestSeller: true,
    rating: 4.4,
  },
  {
    id: 7,
    name: 'Skincare Set - 5 Piece',
    category: 'Beauty & Health',
    price: 59.99,
    description: 'Cleanser, toner, serum, moisturizer, sunscreen. For all skin types.',
    imagePath: 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=300&fit=crop',
    quantity: 60,
    featured: false,
    rating: 4.5,
  },
  {
    id: 8,
    name: 'Backpack - Laptop 15"',
    category: 'Fashion',
    price: 45.99,
    description: 'Padded laptop compartment, water-resistant, multiple pockets.',
    imagePath: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=300&fit=crop',
    quantity: 90,
    featured: false,
    rating: 4.6,
  },
  {
    id: 9,
    name: 'Yoga Mat Non-Slip',
    category: 'Sports & Outdoors',
    price: 29.99,
    description: '6mm thick, eco-friendly TPE, with carrying strap.',
    imagePath: 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=400&h=300&fit=crop',
    quantity: 120,
    featured: false,
    rating: 4.4,
  },
  {
    id: 10,
    name: 'Notebook Set - 3 Pack',
    category: 'Books & Stationery',
    price: 12.99,
    description: 'A5 ruled notebooks, 120 pages each. Ideal for study or work.',
    imagePath: 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=300&fit=crop',
    quantity: 300,
    featured: false,
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
