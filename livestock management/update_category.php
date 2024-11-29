<?php
session_start();
include '../db.php'; // Include the database connection
include '../classes/CategoryManager.php'; // Include the CategoryManager class

$categoryManager = new CategoryManager($conn);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $category = $categoryManager->viewCategory($id);

    if (!$category) {
        echo "Category not found.";
        exit;
    }

    // Handle form submission for updating the category
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $categoryName = $_POST['category_name'];
        $categoryCode = $_POST['category_code'];
        $categoryManager->updateCategory($id, $categoryName, $categoryCode);
        header("Location: livestock_management.php"); // Redirect after updating
        exit();
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
    <title>Update Category</title>
    <link rel="stylesheet" href="../Design/livestock.css">
</head>
<body>
    <div class="container">
        <h2>Update Category</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category['category_name']); ?>" required>
            </div>
            <div class="form -group">
                <label for="category_code">Category Code:</label>
                <input type="text" id="category_code" name="category_code" value="<?php echo htmlspecialchars($category['category_code']); ?>" required>
            </div>
            <button type="submit">Update Category</button>
        </form>
        <a href="livestock_management.php">Cancel</a>
    </div>
</body>
</html>