<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the form
    $seller_name = trim($_POST["seller_name"]);
    $password = trim($_POST["password"]);
    $seller_email = trim($_POST["seller_email"]);
    $business_name = trim($_POST["Business_Name"]);
    $business_email = trim($_POST["Business_email"]);

    // Validate required fields
    if (empty($seller_name) || empty($password) || empty($seller_email) || empty($business_name) || empty($business_email)) {
        header("Location: seller_signup_form.php?error=Please fill in all required fields.");
        exit();
    }

    // Insert seller into the users table
    
    if ($stmt_insert_user->execute()) {
        // Get the user ID of the inserted seller
        $user_id = $stmt_insert_user->insert_id;
        $stmt_insert_user->close();

        // After user is registered, prompt for seller-specific details
        $_SESSION['user_id'] = $user_id; // Store user_id for later use
        header("Location: seller_details_form.php"); // Redirect to seller details form
        exit();
    } else {
        header("Location: seller_signup_form.php?error=Error in registering user.");
        exit();
    }
}

$conn->close();
?>
