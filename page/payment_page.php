<?php
include("Database.php"); // Include the database connection

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"];
    $product_id = intval($_POST["product_id"]);
    $quantity = intval($_POST["quantity"]);
    $payment_method = $_POST["payment_method"];
    $product_price = floatval($_POST["product_price"]);

    // Validate inputs
    if ($quantity < 1) {
        echo "Invalid quantity.";
        exit();
    }

    // Fetch product details to check availability
    $sql = "SELECT quantity, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $available_quantity = intval($product['quantity']);
        $price_per_item = floatval($product['price']);
    } else {
        echo "Product not found.";
        exit();
    }

    // Check if the requested quantity is available
    if ($quantity > $available_quantity) {
        echo "Requested quantity exceeds available stock.";
        exit();
    }

    // Calculate total price
    $total_price = $quantity * $price_per_item;

    // Display payment operation based on the selected payment method
    echo "<h1>Payment Method: " . htmlspecialchars(ucfirst($payment_method)) . "</h1>";
    echo "<p>Total Price: $" . number_format($total_price, 2) . "</p>";

    if ($payment_method === "credit_card") {
        // Credit card payment form
        echo '
            <form action="process_payment.php" method="POST">
                <input type="hidden" name="product_id" value="' . $product_id . '">
                <input type="hidden" name="quantity" value="' . $quantity . '">
                <input type="hidden" name="total_price" value="' . $total_price . '">
                <label for="cc_number">Credit Card Number:</label>
                <input type="text" id="cc_number" name="cc_number" required>
                <label for="cc_expiry">Expiry Date:</label>
                <input type="text" id="cc_expiry" name="cc_expiry" required>
                <label for="cc_cvv">CVV:</label>
                <input type="text" id="cc_cvv" name="cc_cvv" required>
                <button type="submit">Pay Now</button>
            </form>
        ';
    } elseif ($payment_method === "paypal") {
        // PayPal payment button
        echo '
            <p>Click the button below to proceed with PayPal payment:</p>
            <form action="https://www.paypal.com/checkout" method="POST">
                <input type="hidden" name="product_id" value="' . $product_id . '">
                <input type="hidden" name="quantity" value="' . $quantity . '">
                <input type="hidden" name="total_price" value="' . $total_price . '">
                <button type="submit">Pay with PayPal</button>
            </form>
        ';
    } elseif ($payment_method === "bank_transfer") {
        // Bank transfer instructions
        echo '
            <p>Please transfer the total amount of $' . number_format($total_price, 2) . ' to the following bank account:</p>
            <p>Bank: ABC Bank</p>
            <p>Account Number: 123456789</p>
            <p>IFSC Code: ABCD0123456</p>
            <p>Once the transfer is complete, please email the receipt to support@example.com.</p>
        ';
    } else {
        echo "<p>Invalid payment method.</p>";
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
