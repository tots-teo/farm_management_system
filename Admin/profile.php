<?php
session_start();
include '../db.php'; // Include database connection
include '../classes/user.php'; // Include the User class

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Create an instance of the User class
$user = new User($conn);

// Fetch user data from the database
$userId = $_SESSION['user_id'];
$userData = $user->getUserById($userId); // Corrected method name

if (!$userData) {
    echo "User  not found.";
    exit();
}

// Assign the role variable from user data
$role = $userData['role'] ?? null; // Use null coalescing operator to avoid undefined variable

// Handle form submission for updating user profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];

    // Update user profile
    $updateResult = $user->updateUser ($userId, $firstName, $lastName, $email, $phone, $gender);
    if ($updateResult === true) {
        $successMessage = "Profile updated successfully.";
        // Optionally, refresh user data
        $userData = $user->getUserById($userId); // Corrected method name
    } else {
        $errorMessage = "Failed to update profile: " . $updateResult;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../Design/livestock.css"> <!-- Link to your CSS file -->
</head>
<body>
<div class="sidebar">
        <div class="sidebar-header">
            <img src="../mjck farm.jpg" alt="MJCK Farm Logo">
            <h3>MJCK Farm</h3>
        </div>
        <a href="../dashboard/dashboard.php">
            <img src="../assets/dashboard.png" alt="Livestock">Dashboard
        </a>
        <a href="../livestock management/livestock_management.php">
            <img src="../assets/livestock.png" alt="Livestock">Livestock Management
        </a>
        <a href="crop_management.php">
            <img src="../assets/crop.png" alt="Crops">Crop Management
        </a>
        <a href="feed_inventory.php">
            <img src="../assets/feed_inventory.png" alt="Feed Inventory">Feed Inventory
        </a>
        <a href="../Task Manager/Task_Manager.php">
            <img src="../assets/task.png" alt="Task Manager">Task Manager
        </a>
        <?php if ($role === 'Admin'): ?>
            <a href="../Admin/profile.php">
                <img src="../assets/farmer.png" alt="Admin Panel">Admin Panel
            </a>
        <?php endif; ?>
        <a href="logout.php" class="logout-btn">
            <img src="../assets/logout.png" alt="Logout">Logout
        </a>
    </div>
    <div class="container">
        <h2>User Profile</h2>

        <?php if (isset($successMessage)): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($userData['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($userData['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="male" <?php echo ($userData['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($userData['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            <button type="submit">Update Profile</button>
        </form>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>