<?php
session_start();
include '../db.php'; // Include database connection
include '../classes/user.php'; // Include the User class
include '../Sidebar/sidebar.php';

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
    echo "User not found.";
    exit();
}

// Assign the role variable from user data
$role = $userData['role'] ?? null; // Use null coalescing operator to avoid undefined variable

$sidebar = new Sidebar($role); 

// Handle form submission for updating user profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $profileImagePath = $userData['profile_image'] ?? '../path/to/default/image.jpg';

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../Upload Images/profile_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }
        $fileName = uniqid() . '_' . basename($_FILES['profile_image']['name']);
        $targetFilePath = $uploadDir . $fileName;
        
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFilePath)) {
                $profileImagePath = $targetFilePath;
                // Delete old image if it exists
                if (!empty($userData['profile_image']) && file_exists($userData['profile_image'])) {
                    unlink($userData['profile_image']);
                }
            } else {
                $errorMessage = "Failed to upload profile image.";
            }
        } else {
            $errorMessage = "Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.";
        }
    }

    // Update user profile
    $updateResult = $user->updateUser($userId, $firstName, $lastName, $email, $phone, $gender, $profileImagePath);
    if ($updateResult === true) {
        $successMessage = "Profile updated successfully.";
        // Refresh user data
        $userData = $user->getUserById($userId); // Corrected method name
    } else {
        $errorMessage = "Failed to update profile: " . $updateResult;
    }
}

$sidebar->render();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../Design/profile.css"> <!-- Link to your CSS file -->
</head>
<body>

<div class="container">
    <h2>User Profile</h2>

    <?php if (isset($successMessage)): ?>
        <div class="success-message"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <div class="error-message"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
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
        <div class="form-group">
            <label for="profile_image">Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image">
            <div class="profile-image-preview">
                <?php if (!empty($userData['profile_image'])): ?>
                    <img src="<?php echo $userData['profile_image']; ?>" alt="Profile Image" width="150">
                <?php else: ?>
                    <p>No image uploaded</p>
                <?php endif; ?>
            </div>
        </div>
        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
