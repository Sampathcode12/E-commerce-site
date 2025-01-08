<?php
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "";     // Your MySQL password
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: signup.php?error=Please fill in all fields");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            header("Location: user_info.php?user_id=" . $user_id);
            exit();
        } else {
            if ($conn->errno === 1062) {
                header("Location: signup.php?error=Username or email already exists");
            } else {
                header("Location: signup.php?error=An error occurred");
            }
        }
        $stmt->close();
    } else {
        header("Location: signup.php?error=Failed to prepare statement");
    }
}

$conn->close();
?>
