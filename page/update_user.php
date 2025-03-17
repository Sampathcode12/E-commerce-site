<?php
include 'connect.php'; // Database connection file

if (isset($_POST['update'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Validate inputs
    if (!empty($user_id) && !empty($name) && !empty($email) && !empty($role)) {
        // Prepare update query
        $sql = "UPDATE Users SET name = ?, email = ?, role = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);
        
        if ($stmt->execute()) {
            echo "User updated successfully!";
        } else {
            echo "Error updating user: " . $conn->error;
        }
    } else {
        echo "All fields are required!";
    }
}

// Fetch user details for editing
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT * FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<!-- Update Form -->
<form method="POST">
    <input type="hidden" name="user_id" value="<?php echo isset($user['user_id']) ? $user['user_id'] : ''; ?>">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo isset($user['name']) ? $user['name'] : ''; ?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required>
    <label>Role:</label>
    <select name="role" required>
        <option value="Admin" <?php echo (isset($user['role']) && $user['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
        <option value="Customer" <?php echo (isset($user['role']) && $user['role'] == 'Customer') ? 'selected' : ''; ?>>Customer</option>
        <option value="Staff" <?php echo (isset($user['role']) && $user['role'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
    </select>
    <button type="submit" name="update">Update User</button>
</form>
