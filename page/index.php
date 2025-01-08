<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIKAN - E-commerce</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Notification -->
    <div id="notification" class="notification">
        <div class="notification-content">
            <p>You must log in or sign up to access the shop.</p>
            <button id="login-btn" class="btn">Login</button>
            <button id="signup-btn" class="btn">Sign Up</button>
            <button id="close-notification" class="close-btn">X</button>
        </div>
    </div>

    <!-- Main Content (Home Page) -->
   

    <script>
        // Wait for the DOM to load
        document.addEventListener("DOMContentLoaded", function () {
            const notification = document.getElementById("notification");
            const loginBtn = document.getElementById("login-btn");
            const signupBtn = document.getElementById("signup-btn");
            const closeBtn = document.getElementById("close-notification");

            // Show the notification by default
            notification.style.display = "block";

            // Redirect to login when the "Login" button is clicked
            loginBtn.addEventListener("click", function () {
                window.location.href = "login.php";
            });

            // Redirect to sign-up when the "Sign Up" button is clicked
            signupBtn.addEventListener("click", function () {
                window.location.href = "signup.php";
            });

            // Close the notification and redirect to the home page
            closeBtn.addEventListener("click", function () {
               // notification.style.display = "none";
                window.location.href = "Home.php";
            });
        });
    </script>
</body>
</html>
