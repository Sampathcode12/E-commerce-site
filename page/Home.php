<?php
include("Database.php");

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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: calc(33.33% - 20px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: scale(1.02);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-details {
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .product-category {
            font-size: 14px;
            color: #555;
        }

        .product-price {
            font-size: 16px;
            color: #e67e22;
            font-weight: bold;
        }

        .product-description {
            font-size: 14px;
            color: #666;
        }

        .buy-button {
            display: block;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .buy-button:hover {
            background-color: #0056b3;
        }
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
                        <div class="product-details">
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
