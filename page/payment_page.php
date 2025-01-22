<?php
include("Database.php"); // Include the database connection

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"];
    $product_id = intval($_POST["product_id"]);
    $quantity = intval($_POST["quantity"]);
    $payment_method = $_POST["payment_method"];
    $product_price = floatval($_POST["product_price"]);

    // Validate inputs
    if ($quantity < 1) {
        echo "Invalid quantity.";
        exit();
    }

    // Fetch product details to check availability
    $sql = "SELECT quantity, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $available_quantity = intval($product['quantity']);
        $price_per_item = floatval($product['price']);
    } else {
        echo "Product not found.";
        exit();
    }

    // Check if the requested quantity is available
    if ($quantity > $available_quantity) {
        echo "Requested quantity exceeds available stock.";
        exit();
    }

    // Calculate total price
    $total_price = $quantity * $price_per_item;

    // Insert order into the database
    $order_sql = "INSERT INTO orders (user_id, product_id, quantity, total_price, payment_method, order_date) 
                  VALUES (?, ?, ?, ?, ?, NOW())";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("iiids", $user_id, $product_id, $quantity, $total_price, $payment_method);

    if ($order_stmt->execute()) {
        // Update product stock
        $new_quantity = $available_quantity - $quantity;
        $update_stock_sql = "UPDATE products SET quantity = ? WHERE id = ?";
        $update_stock_stmt = $conn->prepare($update_stock_sql);
        $update_stock_stmt->bind_param("ii", $new_quantity, $product_id);
        $update_stock_stmt->execute();

        echo "Order placed successfully!";
    } else {
        echo "Failed to place the order. Please try again.";
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
