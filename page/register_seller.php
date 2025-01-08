<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $business_name = trim($_POST["business_name"]);
    $business_address = trim($_POST["business_address"]);
    $contact_number = trim($_POST["contact_number"]);
    $social_media_links = trim($_POST["social_media_links"]);

    // Handle profile picture upload
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];  // Allowed file extensions
        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);

        // Check for valid file extension
        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            $upload_dir = 'uploads/profile_pictures/'; // Directory where files will be stored
            $file_name = uniqid() . '.' . $file_extension; // Unique file name
            $file_path = $upload_dir . $file_name;

            // Check if the directory exists, if not, create it
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
                // File upload successful
                echo "Profile picture uploaded successfully!";
            } else {
                echo "Error uploading file.";
            }
        } else {
            // Invalid file extension
            header("Location: register_form.php?error=Invalid file type. Allowed types: jpg, jpeg, png, gif.");
            exit();
        }
    }

    // Validate required fields
    if (empty($username) || empty($password) || empty($business_name) || empty($business_address) || empty($contact_number)) {
        header("Location: register_seller_form.php?error=Please fill in all required fields.");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into users table
        $sql_user = "INSERT INTO users (username, password, profile_picture, user_type) VALUES (?, ?, ?, 'seller')";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("sss", $username, $hashed_password, $profile_picture);
        if (!$stmt_user->execute()) {
            throw new Exception("Failed to insert into users table.");
        }
        $user_id = $stmt_user->insert_id;
        $stmt_user->close();

        // Insert into sellers table
        $sql_seller = "INSERT INTO sellers (user_id, business_name, business_address, contact_number, social_media_links) VALUES (?, ?, ?, ?, ?)";
        $stmt_seller = $conn->prepare($sql_seller);
        $stmt_seller->bind_param("issss", $user_id, $business_name, $business_address, $contact_number, $social_media_links);
        if (!$stmt_seller->execute()) {
            throw new Exception("Failed to insert into sellers table.");
        }
        $stmt_seller->close();

        // Commit transaction
        $conn->commit();

        header("Location: seller_dashboard.php?success=You are now registered as a seller.");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: register_seller_form.php?error=" . $e->getMessage());
        exit();
    }
}

$conn->close();
?>
