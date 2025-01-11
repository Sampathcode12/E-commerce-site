<?php
include("Database.php");
include("selle_pannel_hedear.php");

// // Handle form submissions
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if (isset($_POST['add_product'])) {
//         $name = $_POST['name'];
//         $category = $_POST['category'];
//         $price = $_POST['price'];
//         $stock = $_POST['stock'];

//         $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock) VALUES (?, ?, ?, ?)");
//         $stmt->bind_param("ssdi", $name, $category, $price, $stock);
//         $stmt->execute();
//         $stmt->close();
//         echo "Product added successfully!";
//     }

//     if (isset($_POST['update_product'])) {
//         $id = $_POST['id'];
//         $name = $_POST['name'];
//         $category = $_POST['category'];
//         $price = $_POST['price'];
//         $stock = $_POST['stock'];

//         $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, stock=? WHERE id=?");
//         $stmt->bind_param("ssdii", $name, $category, $price, $stock, $id);
//         $stmt->execute();
//         $stmt->close();
//         echo "Product updated successfully!";
//     }

//     if (isset($_POST['delete_product'])) {
//         $id = $_POST['id'];

//         $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
//         $stmt->bind_param("i", $id);
//         $stmt->execute();
//         $stmt->close();
//         echo "Product deleted successfully!";
//     }
// }

// // Fetch products
// $result = $conn->query("SELECT * FROM products");
// $products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Panel</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
   <label> <h1>Product Panel</h1></label>

    
   

    <h2>Update Product</h2>
    <form method="POST" >
        <input type="number" name="id" placeholder="Product ID" required>
        <input type="text" name="name" placeholder="New Product Name">
        <input type="text" name="category" placeholder="New Category">
        <input type="number" step="0.01" name="price" placeholder="New Price">
        <input type="number" name="stock" placeholder="New Stock">
        <button type="submit" name="update_product">Update Product</button>
    </form>

    <h2>Delete Product</h2>
    <form method="POST">
        <input type="number" name="id" placeholder="Product ID" required>
        <button type="submit" name="delete_product">Delete Product</button>
    </form>

    <h2>Search Products</h2>
    <form method="GET">
        <input type="text" name="search" placeholder="Search by Name or Category">
        <button type="submit">Search</button>
    </form>

    <h2>Product List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= $product['name'] ?></td>
                <td><?= $product['category'] ?></td>
                <td><?= $product['price'] ?></td>
                <td><?= $product['stock'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="floating-button">
        <button onclick="window.location.href='add_product.php'">+</button>
    </div>
</body>
</html>
