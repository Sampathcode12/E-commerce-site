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

// Fetch the current user's bank details (if any)
$sql = "SELECT bank_name FROM user_information WHERE 	user_id= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bank_details = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST["current_password"];
    $bank_name = $_POST["bank_name"];
    $account_number = $_POST["account_number"];
    $routing_number = $_POST["routing_number"];

    // Verify the current password
    $password_sql = "SELECT password FROM users WHERE id = ?";
    $password_stmt = $conn->prepare($password_sql);
    $password_stmt->bind_param("i", $user_id);
    $password_stmt->execute();
    $password_result = $password_stmt->get_result();
    $password_row = $password_result->fetch_assoc();
    $password_stmt->close();

    if (password_verify($current_password, $password_row["password"])) {
        // If the user already has bank details, update them; otherwise, insert new details
        if ($bank_details) {
            // Update existing bank details
            $update_sql = "UPDATE bank_details SET bank_name = ?, account_number = ?, routing_number = ? WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssi", $bank_name, $account_number, $routing_number, $user_id);
        } else {
            // Insert new bank details
            $insert_sql = "INSERT INTO bank_details (user_id, bank_name, account_number, routing_number) VALUES (?, ?, ?, ?)";
            $update_stmt = $conn->prepare($insert_sql);
            $update_stmt->bind_param("isss", $user_id, $bank_name, $account_number, $routing_number);
        }

        if ($update_stmt->execute()) {
            header("Location: user_account.php");
            exit();
        } else {
            $error = "Error updating bank details.";
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
    <title>Change Bank Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <h1>Change Bank Details</h1>
        <a href="account.php" class="btn">Back to Account</a>
    </header>

    <div class="account-container">
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="change_bank.php">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="current_password">Current Email:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="bank_name">Bank Name:</label>
            <input type="text" id="bank_name" name="bank_name" value="<?php echo htmlspecialchars($bank_details['bank_name'] ?? ''); ?>" required>

            <label for="account_number">Account Number:</label>
            <input type="text" id="account_number" name="account_number" value="<?php echo htmlspecialchars($bank_details['account_number'] ?? ''); ?>" required>

            <label for="routing_number">Routing Number:</label>
            <input type="text" id="routing_number" name="routing_number" value="<?php echo htmlspecialchars($bank_details['routing_number'] ?? ''); ?>" required>

            <button type="submit">Change Bank Details</button>
        </form>
    </div>
</body>
</html>
