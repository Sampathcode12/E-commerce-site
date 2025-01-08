<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="success-container">
        <h1>Success</h1>
        <p><?php echo htmlspecialchars($_GET['message']); ?></p>
        <a href="main.php" class="btn">Go to Home</a>
    </div>
</body>
</html>
