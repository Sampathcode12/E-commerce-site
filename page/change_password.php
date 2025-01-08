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

// Fetch the current user's password (hashed)
$sql = "SELECT password FROM users WHERE id  = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $new_password_confirm = $_POST["new_password_confirm"];

    if ($new_password === $new_password_confirm) {
        // Verify the current password
        if (password_verify($current_password, $user["password"])) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password
            $update_sql = "UPDATE users SET password = ? WHERE id  = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $hashed_password, $user_id);

            if ($update_stmt->execute()) {
                header("Location: user_account.php");
                exit();
            } else {
                $error = "Error updating password.";
            }
            $update_stmt->close();
        } else {
            $error = "Incorrect current password.";
        }
    } else {
        $error = "New passwords do not match.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <h1>Change Password</h1>
        <a href="account.php" class="btn">Back to Account</a>
    </header>

    <div class="account-container">
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="change_password.php">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="new_password_confirm">Confirm New Password:</label>
            <input type="password" id="new_password_confirm" name="new_password_confirm" required>

            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>
