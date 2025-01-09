
<?php
// Include database connection
require_once 'Database.php'; // Adjust the path to your database connection file

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if user is logged in
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        header("Location: login.php?error=You must log in to register as a seller.");
        exit();
    }

    // Sanitize and validate inputs
    $seller_name = htmlspecialchars($_POST['seller_name']);
    $password = $_POST['password'];
    $seller_email = filter_var($_POST['seller_email'], FILTER_SANITIZE_EMAIL);
    $business_name = htmlspecialchars($_POST['business_name']);
    $business_email = filter_var($_POST['business_email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($seller_email, FILTER_VALIDATE_EMAIL) || !filter_var($business_email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signUp_sellers.php?error=Invalid email format.");
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Start a transaction
    if ($conn) {
        $conn->begin_transaction();
        try {
            // Step 1: Update the `users` table to change `user_type` to 'seller'
            $updateQuery = "UPDATE users SET user_type = 'seller' WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("i", $userId);
            $stmt->execute();

            // Step 2: Insert the seller-specific data into the `seller` table
            $insertQuery = "INSERT INTO seller (seller_id, seller_name, password, seller_email, business_name, business_email) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("isssss", $userId, $seller_name, $hashedPassword, $seller_email, $business_name, $business_email);
            $stmt->execute();

            // Commit the transaction
            $conn->commit();

            // Redirect to seller dashboard
            header("Location: seller.php");
            exit();
        } catch (Exception $e) {
            // Roll back the transaction if any error occurs
            $conn->rollback();
            header("Location: signUp_sellers.php?error=" . urlencode("Failed to register as a seller. Please try again."));
            exit();
        }
    } else {
        header("Location: signUp_sellers.php?error=Database connection failed.");
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="signup-container">
        <h1>Register as a Seller</h1>
        <!-- Display error messages -->
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        ?>

        <form action="signUp_sellers.php" method="POST">
            <!-- Seller Name -->
            <div class="form-group">
                <label for="seller_name">Seller Name:</label>
                <input type="text" id="seller_name" name="seller_name" placeholder="Enter your name" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <!-- Seller Email -->
            <div class="form-group">
                <label for="seller_email">Seller Email:</label>
                <input type="email" id="seller_email" name="seller_email" placeholder="Enter your email" required>
            </div>

            <!-- Business Name -->
            <div class="form-group">
                <label for="business_name">Business Name:</label>
                <input type="text" id="business_name" name="business_name" placeholder="Enter your business name" required>
            </div>

            <!-- Business Email -->
            <div class="form-group">
                <label for="business_email">Business Email:</label>
                <input type="email" id="business_email" name="business_email" placeholder="Enter your business email" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn">Sign Up</button>
        </form>
    </div>
</body>
</html>
