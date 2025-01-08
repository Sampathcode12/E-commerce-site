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

// Fetch current user information
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM user_information WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated data from the form
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $age = $_POST["age"];
    $sex = $_POST["sex"];
    $interests = $_POST["interests"];

    // Update user information in the database
    $update_sql = "UPDATE user_information SET first_name = ?, last_name = ?, phone = ?, address = ?, age = ?, sex = ?, interests = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssiss", $first_name, $last_name, $phone, $address, $age, $sex, $interests, $user_id);
    
    if ($update_stmt->execute()) {
        // Redirect to user account page after successful update
        header("Location: user_account.php");
        exit();
    } else {
        echo "Error updating profile: " . $update_stmt->error;
    }
    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <h1>Edit Profile</h1>
        <a href="account.php" class="btn">Back to Account</a>
    </header>

    <div class="account-container">
        <form method="POST" action="edit_profile.php">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" required>

            <label for="sex">Sex:</label>
            <select id="sex" name="sex" required>
                <option value="Male" <?php if ($user['sex'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($user['sex'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($user['sex'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>

            <label for="interests">Interests:</label>
            <input type="text" id="interests" name="interests" value="<?php echo htmlspecialchars($user['interests']); ?>" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
