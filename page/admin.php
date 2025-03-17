<?php
session_start();
include "Database.php";

// Check if admin is logged in
if (!isset($_SESSION['user_type'])) {
    header('Location: admin_login.php'); // Redirect if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>

<?php include 'admin_header.php'; ?>
<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h2>Welcome, Admin</h2>
    <p>Manage products, users, orders, and website settings.</p>
</div>

</body>
</html>
