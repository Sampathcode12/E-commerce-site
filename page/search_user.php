<?php
include 'connect.php'; // Database connection file

if (isset($_POST['search_query'])) {
    $search_query = trim($_POST['search_query']);

    // Prepare SQL query to search by user_id or partial name match
    $sql = "SELECT * FROM Users WHERE user_id = ? OR name LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%$search_query%";
    $stmt->bind_param("ss", $search_query, $likeQuery);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['user_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['role']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No users found.";
    }
}
?>

<!-- Simple Search Form -->
<form method="POST">
    <input type="text" name="search_query" placeholder="Enter User ID or Name" required>
    <button type="submit">Search</button>
</form>
