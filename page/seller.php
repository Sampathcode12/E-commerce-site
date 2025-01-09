<?php


session_start(); // Start a session to manage user login

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "ecommerce";
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login.php"); // Redirect to login page
    exit();
}

$seller_id = $_SESSION['seller_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Panel</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your styles here -->
</head>
<body>
    <header class="header">
        <h1>Welcome to Seller Panel</h1>
        
        <nav class="navbar">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="Shop.php">Profile</a></li>
            <li><a href="Categories.php">Products</a></li>
            <li><a href="Contact.php">Orders</a></li>
            <li><a href="home.php">Home</a></li>
           

        </ul>
    </nav>
    </header>
<!-- 
    <main>
        <section id="dashboard">
            <h2>Dashboard</h2>
            <?php
            // Fetch summary data
            $sales_query = "SELECT SUM(total_price) as total_sales FROM orders WHERE seller_id = '$seller_id'";
            $sales_result = $conn->query($sales_query);
            $total_sales = $sales_result->fetch_assoc()['total_sales'] ?? 0;

            $orders_query = "SELECT COUNT(*) as total_orders FROM orders WHERE seller_id = '$seller_id'";
            $orders_result = $conn->query($orders_query);
            $total_orders = $orders_result->fetch_assoc()['total_orders'] ?? 0;

            echo "<p>Total Sales: $" . number_format($total_sales, 2) . "</p>";
            echo "<p>Total Orders: $total_orders</p>";
            ?>
        </section>

        <section id="products">
            <h2>Manage Products</h2>
            <a href="add_product.php">Add New Product</a>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $product_query = "SELECT * FROM products WHERE seller_id = '$seller_id'";
                    $product_result = $conn->query($product_query);

                    while ($row = $product_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>\${$row['price']}</td>";
                        echo "<td>{$row['stock']}</td>";
                        echo "<td><a href='edit_product.php?id={$row['id']}'>Edit</a> | <a href='delete_product.php?id={$row['id']}'>Delete</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section id="orders">
            <h2>Order Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Buyer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $orders_query = "SELECT * FROM orders WHERE seller_id = '$seller_id'";
                    $orders_result = $conn->query($orders_query);

                    while ($row = $orders_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['buyer_name']}</td>";
                        echo "<td>\${$row['total_price']}</td>";
                        echo "<td>{$row['status']}</td>";
                        echo "<td><a href='update_order.php?id={$row['id']}'>Update</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section id="profile">
            <h2>Profile Management</h2>
            <a href="edit_profile.php">Edit Profile</a>
        </section>

        <section id="support">
            <h2>Support</h2>
            <p>Contact support at <a href="mailto:support@example.com">support@example.com</a>.</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Your Website Name</p>
    </footer> -->
</body>
</html>
