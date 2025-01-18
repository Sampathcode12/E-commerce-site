<?php
include("Database.php");

$product_id = $_GET['product_ID'] ?? '';
$response = ['exists' => false];

if ($product_id) {
    $sql = "SELECT COUNT(*) AS count FROM products WHERE product_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        $response['exists'] = true;
    }

    $stmt->close();
}

echo json_encode($response);
?>
