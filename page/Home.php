
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIKAN - E-commerce</title>
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- Header Floater -->
    <header class="header">
    <div class="logo">
        <h1>NIKAN</h1>
    </div>
    <nav class="navbar">
        <ul>
            <li><a href="Home.php">Home</a></li>
            <li><a href="#">Shop</a></li>
            <li><a href="#">Categories</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Register</a></li>

        </ul>
    </nav>
    <div class="search">
        <input type="text" placeholder="Search...">
    </div>
    <!-- Customer Account Icon -->
    <div class="account">
        <a href="account.php">
            <i class="fas fa-user-circle"></i>
        </a>
    </div>
</header>


    <!-- Floating Action Button (Quick Add Item) -->
    <div class="floating-button">
        <button onclick="window.location.href='#'">+</button>
    </div>

    <!-- Main Page Content -->
   

    <script src="script.js"></script>
</body>
</html>
