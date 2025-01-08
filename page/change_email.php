<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];

// Fetch the current user's email
$sql = "SELECT email FROM users WHERE id  = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST["current_password"];
    $new_email = $_POST["new_email"];

    // Verify the current password
    $password_sql = "SELECT password FROM users WHERE id  = ?";
    $password_stmt = $conn->prepare($password_sql);
    $password_stmt->bind_param("i", $user_id);
    $password_stmt->execute();
    $password_result = $password_stmt->get_result();
    $password_row = $password_result->fetch_assoc();
    $password_stmt->close();

    if (password_verify($current_password, $password_row["password"])) {
        // Update the email
        $update_sql = "UPDATE users SET email = ? WHERE id  = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_email, $user_id);
        
        if ($update_stmt->execute()) {
            header("Location: user_account.php");
            exit();
        } else {
            $error = "Error updating email.";
        }
        $update_stmt->close();
    } else {
        $error = "Incorrect password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Email</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <h1>Change Email</h1>
        <a href="account.php" class="btn">Back to Account</a>
    </header>

    <div class="account-container">
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="change_email.php">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_email">New Email:</label>
            <input type="email" id="new_email" name="new_email" required>

            <button type="submit">Change Email</button>
        </form>
    </div>
</body>
</html>
