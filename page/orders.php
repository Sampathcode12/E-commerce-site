<?php
include("Database.php"); // Include the database connection

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch the seller's orders
$seller_id = $_SESSION["user_id"];
$sql = "SELECT o.id AS order_id, o.user_id, o.product_id, o.quantity, o.total_price, o.payment_method, o.order_date, p.name AS product_name 
        FROM orders o 
        JOIN products p ON o.product_id = p.id
        WHERE o.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h1>Seller's Order Details</h1>";
echo "<table border='1' cellpadding='10'>";
echo "<thead>
        <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Payment Method</th>
            <th>Order Date</th>
            <th>Action</th>
        </tr>
      </thead>";
echo "<tbody>";

if ($result->num_rows > 0) {
    while ($order = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($order['order_id']) . "</td>";
        echo "<td>" . htmlspecialchars($order['product_name']) . "</td>";
        echo "<td>" . htmlspecialchars($order['quantity']) . "</td>";
        echo "<td>$" . number_format($order['total_price'], 2) . "</td>";
        echo "<td>" . ucfirst(htmlspecialchars($order['payment_method'])) . "</td>";
        echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
        
        // Action buttons (Accept and Reject)
        echo "<td>
                <form method='POST' action='process_order.php' style='display:inline;'>
                    <input type='hidden' name='order_id' value='" . htmlspecialchars($order['order_id']) . "'>
                    <button type='submit' name='action' value='accept'>Accept</button>
                </form>
                <form method='POST' action='process_order.php' style='display:inline;'>
                    <input type='hidden' name='order_id' value='" . htmlspecialchars($order['order_id']) . "'>
                    <button type='submit' name='action' value='reject'>Reject</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No orders found.</td></tr>";
}

echo "</tbody>";
echo "</table>";

?>