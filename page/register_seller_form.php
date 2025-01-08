<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as a Seller</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Register as a Seller</h1>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
    <form action="register_seller.php" method="POST" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="business_name">Business Name:</label>
        <input type="text" id="business_name" name="business_name" required>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

        <label for="business_address">Business Address:</label>
        <textarea id="business_address" name="business_address" required></textarea>

        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number" required>

        <label for="social_media_links">Social Media Links (Optional):</label>
        <textarea id="social_media_links" name="social_media_links"></textarea>

        <button type="submit">Register</button>
    </form>
</body>
</html>
