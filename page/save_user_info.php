<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = intval($_POST["user_id"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $age = intval($_POST["age"]);
    $sex = trim($_POST["sex"]);
    $interests = trim($_POST["interests"]);
    $bank_account_number = !empty($_POST["bank_account_number"]) ? trim($_POST["bank_account_number"]) : null;
    $bank_name = !empty($_POST["bank_name"]) ? trim($_POST["bank_name"]) : null;

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($phone) || empty($address) || empty($age) || empty($sex) || empty($interests)) {
        header("Location: user_info.php?user_id=" . $user_id . "&error=Please fill in all required fields");
        exit();
    }

    // Insert user information into the user_information table
    $sql = "INSERT INTO user_information 
        (user_id, first_name, last_name, phone, address, age, sex, interests, bank_account_number, bank_name) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("issssissss", $user_id, $first_name, $last_name, $phone, $address, $age, $sex, $interests, $bank_account_number, $bank_name);
        if ($stmt->execute()) {
            header("Location: success.php?message=User information saved successfully");
            exit();
        } else {
            header("Location: user_info.php?user_id=" . $user_id . "&error=Failed to save information");
        }
        $stmt->close();
    } else {
        header("Location: user_info.php?user_id=" . $user_id . "&error=Failed to prepare statement");
    }
}

$conn->close();
?>
