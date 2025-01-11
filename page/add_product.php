<?php
include("selle_pannel_hedear.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Product Panel</h1>
    </header>

    <main>
        <section>
            <h2>Add Product</h2>
            <form method="POST">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" placeholder="Product Name" required>

                <label for="category">Category:</label>
                <input type="text" id="category" name="category" placeholder="Category" required>

                <label for="price">Price:</label>
                <input type="number" id="price" step="0.01" name="price" placeholder="Price" required>

                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" placeholder="Stock" required>

                <button type="submit" name="add_product">Add Product</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Product Panel. All Rights Reserved.</p>
    </footer>
</body>

</html>
