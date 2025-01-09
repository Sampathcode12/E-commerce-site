<!DOCTYPE html>
<html lang="en">
<head>

<?php
// Connect to the database
include 'Database.php'; // Ensure this file contains your database connection logic

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $seller_name = $_POST['seller_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
    $seller_email = $_POST['seller_email'];
    $business_name = $_POST['business_name'];
    $business_email = $_POST['business_email'];

    // Insert seller data into the database
    $sql = "INSERT INTO seller (seller_name, password, seller_email, business_name, business_email) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssss", $seller_name, $password, $seller_email, $business_name, $business_email);
        if ($stmt->execute()) {
            // Redirect to the bank information form after successful insertion
            header("Location: bank_information_form.php?seller_id=" . $conn->insert_id);
            exit();
        } else {
            // Handle database insertion error
            header("Location: signup_form.php?error=Failed to register seller");
            exit();
        }
    } else {
        // Handle statement preparation error
        header("Location: signup_form.php?error=Database error");
        exit();
    }
}
?>



    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="signup-container">
        <h1>Register as a Seller</h1>
        <!-- Display error messages if present -->
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
