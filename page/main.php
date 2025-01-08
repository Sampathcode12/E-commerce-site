<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIKAN - E-commerce</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <!-- Notification (initially hidden) -->
    <div id="notification" class="notification">
        <div class="notification-content">
            <p>You must log in or sign up to access the shop.</p>
            <button id="login-btn" class="btn" >Login</button>
            <button id="signup-btn" class="btn" >Sign Up</button>
            <button id="close-notification" class="close-btn">X</button>
        </div>
    </div>

<!--     Main Content (Home Page) -->
     <header class="header">
        <div class="logo">
            <h1>NIKAN</h1> 
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="Home.php">Home</a></li>
                <li><a href="Shop.php">Shop</a></li>
                <li><a href="#">Categories</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>
    
    <script src="script.js"></script>
</body>
</html>
