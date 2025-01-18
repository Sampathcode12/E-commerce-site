<?php
include("Database.php");

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit();
    }
} else {
    echo "Invalid product ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="product-details-page">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
        <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <a href="buy_now.php?product_id=<?php echo $product['id']; ?>" class="buy-button">Buy Now</a>
    </div>
</body>
</html>
