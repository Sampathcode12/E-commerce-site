<?php
include("Database.php"); // Include the database connection

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    $order_id = intval($_POST["order_id"]);
    $action = $_POST["action"];

    // Fetch the order details
    $order_sql = "SELECT * FROM orders WHERE id = ?";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("i", $order_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();

    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();

        if ($action === "accept") {
            // Insert the order details into the sells table
            $sell_sql = "INSERT INTO sells (order_id, user_id, product_id, quantity, total_price, payment_method, sell_date) 
                         VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $sell_stmt = $conn->prepare($sell_sql);
            $sell_stmt->bind_param(
                "iiidss",
                $order['id'],           // Order ID
                $order['user_id'],      // User ID
                $order['product_id'],   // Product ID
                $order['quantity'],     // Quantity
                $order['total_price'],  // Total Price
                $order['payment_method'] // Payment Method
            );

            if ($sell_stmt->execute()) {
                // Delete the order from the orders table
                $delete_sql = "DELETE FROM orders WHERE id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $order_id);

                if ($delete_stmt->execute()) {
                    // Redirect back to orders page with a success message
                    header("Location: orders.php?message=Order accepted and moved to the sales table.");
                    exit();
                } else {
                    echo "<p>Error deleting the order from the orders table: " . htmlspecialchars($delete_stmt->error) . "</p>";
                }
            } else {
                echo "<p>Error adding the order to the sales table: " . htmlspecialchars($sell_stmt->error) . "</p>";
            }
        } elseif ($action === "reject") {
            // Delete the order from the orders table
            $delete_sql = "DELETE FROM orders WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $order_id);

            if ($delete_stmt->execute()) {
                // Redirect back to orders page with a success message
                header("Location: orders.php?message=Order rejected successfully.");
                exit();
            } else {
                echo "<p>Error deleting the order from the orders table: " . htmlspecialchars($delete_stmt->error) . "</p>";
            }
        }
    } else {
        echo "<p>Order not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}

echo '<a href="orders.php">Back to Orders</a>';
?>