<?php
// Connect to the database
include 'Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $seller_id = $_POST['seller_id'];
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $ifsc_code = $_POST['ifsc_code'];

    // Insert bank data into the database
    $sql = "INSERT INTO bank_details (seller_id, bank_name, account_number, ifsc_code) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("isss", $seller_id, $bank_name, $account_number, $ifsc_code);
        if ($stmt->execute()) {
            // Redirect to a success page or dashboard
            header("Location: success.php");
            exit();
        } else {
            // Handle database insertion error
            header("Location: bank_information_form.php?error=Failed to save bank details&seller_id=" . $seller_id);
            exit();
        }
    } else {
        // Handle statement preparation error
        header("Location: bank_information_form.php?error=Database error&seller_id=" . $seller_id);
        exit();
    }
}
?>
