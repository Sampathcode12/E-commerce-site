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
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $user_type = $_POST["user_type"];
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $age = intval($_POST["age"]);
    $sex = $_POST["sex"];
    $interests = trim($_POST["interests"]);
    $bank_account_number = trim($_POST["bank_account_number"]);
    $bank_name = trim($_POST["bank_name"]);

    // Validate input fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($user_type) || empty($phone) || empty($address) || empty($age) || empty($sex) || empty($interests)) {
        header("Location: signup.php?error=Please fill in all required fields");
        exit();
    }

    // Hash the password for storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $sql = "INSERT INTO users (first_name, last_name, email, password, user_type, phone, address, age, sex, interests, bank_account_number, bank_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ssssssssssss", $first_name, $last_name, $email, $hashed_password, $user_type, $phone, $address, $age, $sex, $interests, $bank_account_number, $bank_name);

        // Execute the query and check for success
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            header("Location: login.php?user_id=" . $user_id); // Redirect to user information page
            exit();
        } else {
            if ($conn->errno === 1062) { // Error code for duplicate entries (email or username)
                header("Location: signup.php?error=Email already exists");
            } else {
                header("Location: signup.php?error=An error occurred during signup");
            }
        }

        $stmt->close();
    } else {
        header("Location: signup.php?error=Failed to prepare SQL statement");
    }
}

$conn->close();
?>
