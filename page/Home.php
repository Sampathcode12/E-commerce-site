
<?php
include 'header.php';

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

   


    <!-- Floating Action Button (Quick Add Item) -->
    <div class="floating-button">
        <button onclick="window.location.href='#'">+</button>
    </div>

    <!-- Main Page Content -->
   

    <script src="script.js"></script>
</body>
</html>
