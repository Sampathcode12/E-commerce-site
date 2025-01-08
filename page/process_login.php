<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";


   $conn;                 // Connection instance


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $user_type = isset($_POST["user_type"]) ? $_POST["user_type"] : 'customer';

    // Check if it's an admin login (hardcoded check)
    if ($email === 'admin@example.com' && $password === 'adminpassword') {
        // Hard-code admin login
        $_SESSION["user_id"] = 1;  // Set a fake admin user ID for now
        $_SESSION["user_type"] = 'admin';
        header("Location:account.php");
        exit();
    }

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Please fill in all fields");
        exit();
    }

    // Check user credentials
    $sql = "SELECT id, password, user_type FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password, $db_user_type);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Store session data
                $_SESSION["user_id"] = $user_id;
                $_SESSION["user_type"] = $db_user_type;

                // Redirect to the appropriate dashboard
                if ($db_user_type === "admin") {
                    header("Location: admin.php");
                } elseif ($db_user_type === "customer") {
                    header("Location: Home.php");
                } elseif ($db_user_type === "seller") {
                    header("Location: Home.php");
                }
                exit();
            } else {
                header("Location: login.php?error=Invalid email or password");
                exit();
            }
        } else {
            header("Location: login.php?error=Invalid email or password");
            exit();
        }

        $stmt->close();
    } else {
        header("Location: login.php?error=Database error. Please try again.");
        exit();
    }
}

$conn->close();
?>
