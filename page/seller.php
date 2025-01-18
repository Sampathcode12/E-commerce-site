<?php
include("selle_pannel_hedear.php");
include("Database.php");

session_start(); // Start a session to manage user login

// Database connection


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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="button.css">
    <script src="script.js" defer></script>
    <!-- !-- Include your styles here --> -->
</head>
<body>

   
<div class="floating-button">
        <button onclick="window.location.href='add_product.php'">+</button>
    </div>

</body>
</html>
