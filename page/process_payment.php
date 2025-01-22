<?php
include("Database.php"); // Include the database connection

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"];
    $product_id = intval($_POST["product_id"]);
    $quantity = intval($_POST["quantity"]);
    $total_price = floatval($_POST["total_price"]);

    // Validate input data
    if ($quantity < 1 || $total_price <= 0) {
        echo "Invalid payment details.";
        exit();
    }

    // Check the payment method
    if (isset($_POST["cc_number"]) && isset($_POST["cc_expiry"]) && isset($_POST["cc_cvv"])) {
        $payment_method = "credit_card";
        $cc_number = htmlspecialchars($_POST["cc_number"]);
        $cc_expiry = htmlspecialchars($_POST["cc_expiry"]);
        $cc_cvv = htmlspecialchars($_POST["cc_cvv"]);

        // Simulate credit card validation
        if (!preg_match("/^\d{16}$/", $cc_number) || !preg_match("/^\d{2}\/\d{2}$/", $cc_expiry) || !preg_match("/^\d{3}$/", $cc_cvv)) {
            echo "Invalid credit card details.";
            exit();
        }
    } elseif (isset($_POST["paypal"])) {
        $payment_method = "paypal";
        // Simulate PayPal validation
        echo "Processing PayPal payment...";
        // Redirect or process further as needed
        exit();
    } elseif (isset($_POST["bank_transfer"])) {
        $payment_method = "bank_transfer";
        // Simulate bank transfer validation
        echo "Processing bank transfer...";
        // Redirect or process further as needed
        exit();
    } else {
        echo "Invalid payment method.";
        exit();
    }

    // Insert the payment record into the database
    $order_sql = "INSERT INTO orders (user_id, product_id, quantity, total_price, payment_method, order_date) 
                  VALUES (?, ?, ?, ?, ?, NOW())";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("iiids", $user_id, $product_id, $quantity, $total_price, $payment_method);

    if ($order_stmt->execute()) {
        // Reduce the stock in the products table
        $update_stock_sql = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
        $update_stock_stmt = $conn->prepare($update_stock_sql);
        $update_stock_stmt->bind_param("ii", $quantity, $product_id);
        $update_stock_stmt->execute();

        // Display success message
        echo "<h1>Payment Successful!</h1>";
        echo "<p>Order ID: " . $order_stmt->insert_id . "</p>";
        echo "<p>Total Price: $" . number_format($total_price, 2) . "</p>";
        echo "<p>Payment Method: " . ucfirst($payment_method) . "</p>";
        echo '<a href="index.php">Return to Home</a>';
    } else {
        echo "Failed to process the order. Please try again.";
    }
} else {
    echo "Invalid request.";
    echo '<a href="index.php">Return to Home</a>';
    exit();
}
?>
