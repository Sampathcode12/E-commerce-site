<?php
include("Database.php"); // Include the database connection

// Check if product_id is provided in the URL
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Fetch the product details from the database
    $sql = "SELECT id, name, category, price, image_path, description, quantity FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid product.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="buy.css">
</head>
<body>
    <div class="product-detail-container">
        <h1>Buy Now</h1>
        <div class="product-detail">
            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-detail-image">
            <div class="product-detail-info">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p>Category: <?php echo ucfirst(htmlspecialchars($product['category'])); ?></p>
                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <form action="payment_page.php" method="POST" onsubmit="return validateQuantity(<?php echo $product['quantity']; ?>);">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" min="1" value="1" required>

                    <label for="payment_method">Payment Method:</label>
                    <select name="payment_method" id="payment_method" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>

                    <button type="submit" class="place-order-button">Place Order</button>
                </form>
                <p id="error-message" style="color: red; display: none;"></p>
            </div>
        </div>
    </div>

    <script>
        function validateQuantity(availableQuantity) {
            const quantityInput = document.getElementById('quantity');
            const errorMessage = document.getElementById('error-message');

            if (parseInt(quantityInput.value) > availableQuantity) {
                errorMessage.textContent = `Only ${availableQuantity} item(s) are available. Please adjust the quantity.`;
                errorMessage.style.display = 'block';
                return false;
            }

            errorMessage.style.display = 'none';
            return true;
        }
    </script>
</body>
</html>
