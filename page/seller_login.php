<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";


   $conn;                 // Connection instance


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    

    // Check if it's an admin login (hardcoded check)
    if ($email === 'admin@example.com' && $password === 'adminpassword') {
        // Hard-code admin login
        $_SESSION["user_id"] = 1;  // Set a fake admin user ID for now
        $_SESSION["user_type"] = 'admin';
        header("Location:account.php");
        exit();
    }

    if (empty($email) || empty($password)) {
        header("Location: seller_login.php?error=Please fill in all fields");
        exit();
    }

    // Check user credentials
    $sql = "SELECT seller_email, password FROM seller WHERE seller_email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Store session data
                $_SESSION["seller_id"] = $user_id;
                
                header("Location: seller.php");
                exit();
                // Redirect to the appropriate dashboard
                
            } else {
                header("Location: seller_login.php?error=Invalid email or password");
                exit();
            }
        } else {
            header("Location: seller_login.php?error=Invalid email or password");
            exit();
        }

        $stmt->close();
    } else {
        header("Location: seller_login.php?error=Database error. Please try again.");
        exit();
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h1>Seller Login</h1>
        <!-- Display error messages -->
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        ?>

        <form action="seller_login.php" method="POST">
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn">Login</button>
        </form>

        <!-- <p>
            Not a seller? <a href="signUp_sellers.php">Register here</a>.
        </p> -->
    </div>
</body>
</html>
