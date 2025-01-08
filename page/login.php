<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        ?>
        
        <!-- Add a hidden field if the user is logging in as admin -->
        <?php
        if (isset($_GET['admin'])) {
            echo "<p class='info'>Logging in as admin. Please proceed.</p>";
            echo "<input type='hidden' name='user_type' value='admin'>";
        }
        ?>

        <form action="process_login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <!-- Hardcode Admin login check (for testing purposes) -->
            <?php
            if (!isset($_GET['admin'])) {
                echo "<button type='submit' class='btn'>Login</button>";
            } else {
                echo "<button type='submit' class='btn'>Login as Admin</button>";
            }
            ?>
        </form>
        
        <p>
            Not registered? <a href="signup.php">Sign up here</a>.
        </p>
    </div>
</body>
</html>
