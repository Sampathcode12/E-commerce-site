<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>
    <!-- Link to your external CSS file -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
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

    // Fetch user information
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT * FROM user_information WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    ?>

    <header class="header">
        <h1>User Account</h1>
        <a href="logout.php" class="btn-logout">Logout</a>
    </header>

    <div class="account-container">
        <!-- Profile Section -->
        <section class="profile">
            <h2>Profile</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user["first_name"] . " " . $user["last_name"]); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user["email"]); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user["phone"]); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user["address"]); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($user["age"]); ?></p>
            <p><strong>Sex:</strong> <?php echo htmlspecialchars($user["sex"]); ?></p>
            <p><strong>Interests:</strong> <?php echo htmlspecialchars($user["interests"]); ?></p>
            <a href="edit_profile.php" class="btn">Edit Profile</a>
        </section>

        <!-- Account Management -->
        <section class="account-management">
            <h2>Account Management</h2>
            <a href="change_password.php" class="btn">Change Password</a>
            <a href="change_email.php" class="btn">Change Email</a>
            <a href="Banck_information.php" class="btn">Change Banck information</a>
        </section>

        <!-- Order History -->
        <section class="order-history">
            <h2>Order History</h2>
            <p>No orders found. (Dynamic content based on database)</p>
        </section>

        <!-- Bank Details -->
        <section class="bank-details">
            <h2>Bank Details</h2>
            <p>No bank details saved. (Optional content)</p>
        </section>
    </div>
</body>
</html>
