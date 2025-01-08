<?php
session_start();

if (!isset($_GET['success'])) {
    header("Location: login.php");
    exit();
}

$success_message = htmlspecialchars($_GET['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome to the Seller Dashboard</h1>
    <p><?php echo $success_message; ?></p>
    <a href="manage_products.php" class="btn">Manage Products</a>
    <a href="view_orders.php" class="btn">View Orders</a>
    <a href="account_settings.php" class="btn">Account Settings</a>
</body>
</html>
