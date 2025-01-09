<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Information</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="bank-info-container">
        <h1>Bank Information</h1>
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        ?>
        <form action="save_bank_information.php" method="POST">
            <input type="hidden" name="seller_id" value="<?php echo htmlspecialchars($_GET['seller_id']); ?>">

            <div class="form-group">
                <label for="bank_name">Bank Name:</label>
                <input type="text" id="bank_name" name="bank_name" placeholder="Enter your bank name" required>
            </div>

            <div class="form-group">
                <label for="account_number">Account Number:</label>
                <input type="text" id="account_number" name="account_number" placeholder="Enter your account number" required>
            </div>

            <div class="form-group">
                <label for="ifsc_code">IFSC Code:</label>
                <input type="text" id="ifsc_code" name="ifsc_code" placeholder="Enter the IFSC code" required>
            </div>

            <button type="submit" class="btn">Save Bank Information</button>
        </form>
    </div>
</body>
</html>
