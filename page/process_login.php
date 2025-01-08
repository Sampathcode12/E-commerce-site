<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Please fill in all fields");
        exit();
    }

    // Check if email exists in the database
    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if a user with this email exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Start a session and store user ID
                $_SESSION["user_id"] = $user_id;

                // Redirect to the home page or dashboard
                header("Location: Home.php");
                exit();
            } else {
                // Incorrect password
                header("Location: login.php?error=Invalid email or password");
                exit();
            }
        } else {
            // Email not found
            header("Location: login.php?error=Invalid email or password");
            exit();
        }

        $stmt->close();
    } else {
        header("Location: login.php?error=Failed to prepare statement");
    }
}

$conn->close();
?>
