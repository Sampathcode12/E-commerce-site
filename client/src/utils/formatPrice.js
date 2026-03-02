/** Format price as Rs. X,XXX.00 for display across the site */
export function formatPrice(price) {
  const num = Number(price)
  if (Number.isNaN(num)) return 'Rs. 0.00'
  return `Rs. ${num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}
