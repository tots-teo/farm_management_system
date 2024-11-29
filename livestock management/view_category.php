<?php
session_start();
include '../db.php'; // Include the database connection
include '../classes/CategoryManager.php'; // Include the CategoryManager class

if (isset($_GET['id'])) {
    $categoryManager = new CategoryManager($conn);
    $category = $categoryManager->viewCategory($_GET['id']);

    if (!$category) {
        echo "Category not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
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
    <div class="container">
        <h2>Category Details</h2>
        <p><strong>Category Name:</strong> <?php echo htmlspecialchars($category['category_name']); ?></p>
        <p><strong>Category Code:</strong> <?php echo htmlspecialchars($category['category_code']); ?></p>
        <p><strong>Image:</strong> <img src="<?php echo htmlspecialchars($category['set_picture']); ?>" alt="Category Image" style="width: 100px; height: auto;"></p>
        <a href="livestock_management.php">Back to Livestock Management</a>
    </div>
</body>
</html>