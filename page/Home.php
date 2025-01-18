
<?php
include("Database.php");
include("header.php");

// Fetch products from the database
$sql = "SELECT id, name, category, price, image_path, description FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="home.css">

    
    <style>
        
        
    </style>
</head>

<body>
    <div class="container">
        <h1>Products</h1>
        <div class="product-grid">
            <?php if ($result->num_rows > 0) : ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="product-card">
                        <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>" class="product-image">
                        <a href="buy_now.php?product_id=<?php echo $row['id']; ?>" class="buy-button">Buy Now</a>
                        <div class="product-details" >
                            <h3 class="product-name"><?php echo $row['name']; ?></h3>
                            <p class="product-category">Category: <?php echo ucfirst($row['category']); ?></p>
                            <p class="product-price">$<?php echo number_format($row['price'], 2); ?></p>
                            <p class="product-description"><?php echo substr($row['description'], 0, 50) . '...'; ?></p>
                            <a href="buy_now.php?product_id=<?php echo $row['id']; ?>" class="buy-button">Buy Now</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No products available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>





















