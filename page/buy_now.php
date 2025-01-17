<?php
include("Database.php");

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details from the database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo "<h1>Proceed to Buy: " . htmlspecialchars($product['name']) . "</h1>";
        echo "<p>Price: $" . number_format($product['price'], 2) . "</p>";
        // Add your payment integration or purchase logic here
    } else {
        echo "<p>Product not found.</p>";
    }

    $stmt->close();
} else {
    echo "<p>No product selected for purchase.</p>";
}
?>
