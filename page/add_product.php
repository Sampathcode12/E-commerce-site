
<?php
include("selle_pannel_hedear.php");
include("Database.php");

session_start();
if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login.php"); // Redirect to login page
    exit();
}

?>

<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $sub_category = $_POST['sub-category'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle file upload
    $target_dir = "uploads/";
    $image_path = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
        // Insert data into the database
        $sql = "INSERT INTO products (name, category, sub_category, price, image_path, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $category, $sub_category, $price, $image_path, $description);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Product added successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error adding product: " . $conn->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color: red;'>Error uploading image.</p>";
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
                "home-appliances": ["Kitchen Appliances", "Cleaning Appliances", "Air Conditioners"],
                books: ["Fiction", "Non-Fiction", "Educational", "Comics"],
                others: ["Miscellaneous"]
            };

            // Populate sub-category dropdown
            if (subCategories[category]) {
                subCategories[category].forEach((sub) => {
                    const option = document.createElement("option");
                    option.value = sub.toLowerCase().replace(/\s+/g, "-");
                    option.textContent = sub;
                    subCategory.appendChild(option);
                });
            }
        }
    </script>
</head>
<div style="padding: 5%;">
<body>
    <main>
        <section class="product-section">
            <div class="section-container">
                <h2 class="form-title">Add Product</h2>
                <form action="add_product.php" method="POST" enctype="multipart/form-data" class="product-form">
                    <!-- Product Name -->
                    <label for="name" class="form-label">Product Name:</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Enter product name" required>

                    <!-- Select Category -->
                    <label for="category" class="form-label">Select Category:</label>
                    <select id="category" name="category" class="form-input" onchange="updateSubCategories()" required>
                        <option value="" disabled selected>Choose a category</option>
                        <option value="electronics">Electronics</option>
                        <option value="fashion">Fashion</option>
                        <option value="home-appliances">Home Appliances</option>
                        <option value="books">Books</option>
                        <option value="others">Others</option>
                    </select>

                    <!-- Sub-Category -->
                    <label for="sub-category" class="form-label">Select Sub-Category:</label>
                    <select id="sub-category" name="sub-category" class="form-input" required>
                        <option value="" disabled selected>Select sub-category</option>
                    </select>

                    <!-- Price -->
                    <label for="price" class="form-label">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" class="form-input" placeholder="Enter price" required>

                    <!-- Image -->
                    <label for="image" class="form-label">Upload Image:</label>
                    <input type="file" id="image" name="image" class="form-input" accept="image/*" required>

                    <!-- Description -->
                    <label for="description" class="form-label">Description:</label>
                    <textarea id="description" name="description" class="form-input" placeholder="Enter product description" rows="4" required></textarea>

                    <!-- Submit Button -->
                    <button type="submit" name="add_product" class="form-button">Add Product</button>
                </form>
            </div>
        </section>
    </main>
</body>
</div>
</html>
