<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="sign_up.css">

    <script src="script.js" defer></script>
</head>
<body>
    

    <div class="signup-container">
        <h1>Sign Up</h1>
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        ?>
        <form action="signup_process.php" method="POST">

            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>

            <div class="form-group">
                <label for="sex">Sex:</label>
                <select id="sex" name="sex" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="interests">Interests:</label>
                <textarea id="interests" name="interests" required></textarea>
            </div>

            <h2>Bank Details (Optional)</h2>

            <div class="form-group">
                <label for="bank_account_number">Bank Account Number:</label>
                <input type="text" id="bank_account_number" name="bank_account_number">
            </div>

            <div class="form-group">
                <label for="bank_name">Bank Name:</label>
                <input type="text" id="bank_name" name="bank_name">
            </div>

            <button type="submit">Sign Up</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2025 My Website. All rights reserved.</p>
    </footer>

    <div class="floating-button">
        <button onclick="scrollToTop()">&#8679;</button>
    </div>

    <script>
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
