<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login_page/login.php");
    exit();
}

// Initialize the role variable from the session
$role = $_SESSION['role'];

// Include necessary files
include '../db.php';
include '../Sidebar/sidebar.php';

// Create instances
$sidebar = new Sidebar($role);

// Fetch category details
$categoryId = $_GET['id'] ?? null;
$category = null;

if ($categoryId) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    }
}

if (!$category) {
    echo "Category not found.";
    exit();
}

$sidebar->render();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Category</title>
    <link rel="stylesheet" href="../Design/livestock.css">
</head>
<body>

<div class="main-content">
    <div class="container">
        <h2>Category Details</h2>

        <!-- Category Details Section -->
        <div class="category-details">
            <div class="form-group">
                <label>Category Name:</label>
                <p><?php echo htmlspecialchars($category['category_name']); ?></p>
            </div>

            <div class="form-group">
                <label>Category Code:</label>
                <p><?php echo htmlspecialchars($category['category_code']); ?></p>
            </div>

            <div class="form-group">
                <label>Category Image:</label>
                <?php if ($category['set_picture']): ?>
                    <img src="<?php echo htmlspecialchars($category['set_picture']); ?>" alt="Category Image" style="width: 100%; height: auto;">
                <?php else: ?>
                    <p>No image available.</p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <a href="livestock_management.php" class="btn">Back to Categories</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
