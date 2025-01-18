<?php
include 'header.php';

session_start();
if (!isset($_SESSION["seller_id"])) {
    header("Location: login.php");
    exit();
}
?>



<body>





    <?php
   

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
    $sql = "SELECT * FROM users
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Check if the user is an Admin
    $isAdmin = isset($user["user_type"]) && $user["user_type"] === "admin";
    
    // Fetching orders dynamically for non-admin users
    $orders = [];
    $order_sql = "SELECT * FROM users WHERE id = ?";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("i", $user_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    while ($order = $order_result->fetch_assoc()) {
        $orders[] = $order;
    }
    $order_stmt->close();

    // Fetching bank details
    $bank_sql = "SELECT bank_account_number, bank_name FROM users WHERE id = ?";
    $bank_stmt = $conn->prepare($bank_sql);
    $bank_stmt->bind_param("i", $user_id);
    $bank_stmt->execute();
    $bank_result = $bank_stmt->get_result();
    $bank_details = $bank_result->fetch_assoc();
    $bank_stmt->close();

    $conn->close();
    ?>

    <header class="header">
        <h1>User Account</h1>
        <a href="logout.php" class="btn-logout">Logout</a>
        <a href="home.php" class="btn-home">Home</a>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="acount.css">

    </header>

    <div class="account-container">
        <!-- Profile Section -->
        <section class="profile">
            <h2>Profile</h2>

<p><strong>Name:</strong> <?php echo htmlspecialchars($user["first_name"] . " " . $user["last_name"]); ?></p><br>
            
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user["email"]); ?></p> <br>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user["phone"]); ?></p><br>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user["address"]); ?></p><br>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($user["age"]); ?></p><br>
            <p><strong>Sex:</strong> <?php echo htmlspecialchars($user["sex"]); ?></p><br>
            <p><strong>Interests:</strong> <?php echo htmlspecialchars($user["interests"]); ?></p><br>
            <p><strong>User type:</strong> <?php echo htmlspecialchars($user["user_type"]); ?></p><br>



          
            <a href="edit_profile.php" class="btn">Edit Profile</a>
        </section>

        <!-- Account Management -->
        <section class="account-management">
            <h2>Account Management</h2>
            <a href="change_password.php" class="btn">Change Password</a >
            <a href="change_email.php" class="btn">Change Email</a>
            <a href="Bank_information.php" class="btn">Change Bank Information</a>
            <?php if ($user["user_type"] === "seller"): ?>
                <a href="seller.php" class="btn">Go to Seller Account</a>
            <?php elseif ($user["user_type"] === "customer"): ?>
                <a href="signUp_sellers.php" class="btn">Create a Seller Account</a>
            <?php endif; ?>
        </section>
        <!-- Conditional Buttons -->
        <section class="user-actions">
            
        </section>

        <?php if ($isAdmin): ?>
            <!-- Admin-Specific Content -->
            <section class="admin-dashboard">
                <h2>Admin Dashboard</h2>
                <p>Welcome, Admin! Here are your management tools:</p>
                <a href="manage_users.php" class="btn">Manage Users</a>
                <a href="view_reports.php" class="btn">View Reports</a>
                <a href="site_settings.php" class="btn">Site Settings</a>
            </section>
        <?php else: ?>
            <!-- Order History for non-Admins -->
            <section class="order-history">
                <h2>Order History</h2>
                <?php if (count($orders) > 0): ?>
                    <ul>
                        <?php foreach ($orders as $order): ?>
                            <li>Order #<?php echo htmlspecialchars($order["order_id"]); ?> - Status: <?php echo htmlspecialchars($order["status"]); ?> - Total: $<?php echo htmlspecialchars($order["total_amount"]); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </section>

            <!-- Bank Details -->
            <section class="bank-details">
                <h2>Bank Details</h2>
                <?php if ($bank_details && !empty($bank_details["bank_account_number"])): ?>
                    <p><strong>Bank Account Number:</strong> <?php echo htmlspecialchars($bank_details["bank_account_number"]); ?></p>
                    <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($bank_details["bank_name"]); ?></p>
                <?php else: ?>
                    <p>No bank details saved.</p>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</body>
</html>
