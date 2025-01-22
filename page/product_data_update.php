<?php

include("Database.php");

session_start();
if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login.php"); // Redirect to login page
    exit();
}

// Initialize variables
$product = null;
$message = "";

// Handle product search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_product'])) {
    $product_id = htmlspecialchars($_POST['search_product_id']);

    // Fetch product details by product ID
    $search_sql = "SELECT * FROM products WHERE product_ID = ?";
    $search_stmt = $conn->prepare($search_sql);
    $search_stmt->bind_param("s", $product_id);
    $search_stmt->execute();
    $result = $search_stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $message = "Product not found.";
    }
}

// Handle product update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $name = htmlspecialchars($_POST['name']);
    $product_quantity = htmlspecialchars($_POST['quantity']);
    $product_id = htmlspecialchars($_POST['product_ID']);
    $category = htmlspecialchars($_POST['category']);
    $sub_category = htmlspecialchars($_POST['sub-category']);
    $price = floatval($_POST['price']);
    $description = htmlspecialchars($_POST['description']);

    // Handle file upload if a new image is provided
    $image_path = isset($product['image_path']) ? $product['image_path'] : ""; // Keep the old image path by default
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image_path = $target_dir . basename($_FILES["image"]["name"]);

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowed_types) && $_FILES['image']['size'] <= 2 * 1024 * 1024) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                $message = "Error uploading image.";
            }
        } else {
            $message = "Invalid file type or file size exceeds 2MB.";
        }
    }

    // Update product details in the database
    $update_sql = "UPDATE products SET name=?, quantity=?, category=?, sub_category=?, price=?, image_path=?, description=? WHERE product_ID=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssdss", $name, $product_quantity, $category, $sub_category, $price, $image_path, $description, $product_id);

    if ($update_stmt->execute()) {
        $message = "Product updated successfully!";
    } else {
        $message = "Error updating product: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Panel</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="add_product.css">
    <link rel="stylesheet" href="produc_update.css">

    <script src="script.js" defer></script>
    <script>
        // JavaScript to update sub-categories based on selected category
        function updateSubCategories() {
            const subCategory = document.getElementById("sub-category");
            const category = document.getElementById("category").value;

            // Clear existing sub-categories
            subCategory.innerHTML = '<option value="" disabled selected>Select sub-category</option>';

            // Define sub-categories for each category
            const subCategories = {
                electronics: ["Mobile Phones", "Laptops", "Cameras", "Accessories"],
                fashion: ["Men's Clothing", "Women's Clothing", "Footwear", "Accessories"],
                home_appliances: ["Kitchen Appliances", "Large Appliances", "Home Decor", "Cleaning Supplies"],
                books: ["Fiction", "Non-Fiction", "Textbooks", "Magazines"]
            };

            // Populate sub-categories based on selected category
            if (subCategories[category]) {
                subCategories[category].forEach(sub => {
                    const option = document.createElement("option");
                    option.value = sub.toLowerCase().replace(/ /g, "_");
                    option.textContent = sub;
                    subCategory.appendChild(option);
                });
            }
        }
    </script>
</head>

<body>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="container">
        <h1>Product Panel</h1>

        <!-- Product Search Form -->
        <form method="POST" class="search-form">
            <label for="search_product_id">Search Product by ID:</label>
            <input type="text" name="search_product_id" id="search_product_id" required>
            <button type="submit" name="search_product">Search</button>
        </form>

        <!-- Product Update Form -->
        <?php if ($product): ?>
            <form method="POST" enctype="multipart/form-data" class="update-form">
                <input type="hidden" name="product_ID" value="<?= htmlspecialchars($product['product_ID']); ?>">

                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" required>

                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($product['quantity']); ?>" required>

                <label for="category">Category:</label>
                <select name="category" id="category" onchange="updateSubCategories()" required>
                    <option value="" disabled>Select category</option>
                    <option value="electronics" <?= $product['category'] === 'electronics' ? 'selected' : ''; ?>>Electronics</option>
                    <option value="fashion" <?= $product['category'] === 'fashion' ? 'selected' : ''; ?>>Fashion</option>
                    <option value="home_appliances" <?= $product['category'] === 'home_appliances' ? 'selected' : ''; ?>>Home Appliances</option>
                    <option value="books" <?= $product['category'] === 'books' ? 'selected' : ''; ?>>Books</option>
                </select>

                <label for="sub-category">Sub-Category:</label>
                <select name="sub-category" id="sub-category" required>
                    <option value="<?= htmlspecialchars($product['sub_category']); ?>" selected><?= ucfirst(str_replace('_', ' ', $product['sub_category'])); ?></option>
                </select>

                <label for="price">Price:</label>
                <input type="text" name="price" id="price" value="<?= htmlspecialchars($product['price']); ?>" required>

                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($product['description']); ?></textarea>

                <label for="image">Product Image:</label>
                <input type="file" name="image" id="image" accept="image/*">
                <p>Current Image: <?= htmlspecialchars($product['image_path']); ?></p>

                <button type="submit" name="update_product">Update Product</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
