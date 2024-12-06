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
include '../livestock management/CategoryManager.php';
include '../Sidebar/sidebar.php';

// Create instances
$categoryManager = new CategoryManager($conn);
$sidebar = new Sidebar($role);

// Fetch category details for the update form
$categoryId = $_GET['id'] ?? null;

if ($categoryId) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $category = $stmt->get_result()->fetch_assoc();
}

// Handle form submission for updating category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_category'])) {
        $categoryName = $_POST['category'];
        $categoryCode = $_POST['code'];
        $sex = $_POST['sex'];
        $age = $_POST['age'];
        $weight = $_POST['weight'];

        // Update category information in the database
        $stmt = $conn->prepare("UPDATE categories SET category_name = ?, category_code = ?, sex = ?, age = ?, weight = ? WHERE id = ?");
        $stmt->bind_param('sssiid', $categoryName, $categoryCode, $sex, $age, $weight, $categoryId);
        $stmt->execute();

        // Handle file upload if a new image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $fileName = $_FILES['image']['name'];
            $tempName = $_FILES['image']['tmp_name'];
            $uniqueFileName = uniqid() . '_' . $fileName;
            $folder = '../livestock management/Upload Images/' . $uniqueFileName;

            // Update category picture in the database
            $stmt = $conn->prepare("UPDATE categories SET set_picture = ? WHERE id = ?");
            $stmt->bind_param('si', $folder, $categoryId);
            $stmt->execute();

            // Move uploaded file to the specified folder
            if (move_uploaded_file($tempName, $folder)) {
                echo "File uploaded successfully.";
            } else {
                echo "File upload failed.";
            }
        }

        // Redirect after updating
        header("Location: livestock_management.php");
        exit();
    }
}

$sidebar->render();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>
    <link rel="stylesheet" href="../Design/viewUpdatelivestock.css">
</head>
<body>

<div class="main-content">
    <div class="container">
        <h2>Update Category</h2>

        <!-- Update Category Form -->
        <div class="form-container">
            <form method="POST" action="update_category.php?id=<?php echo $categoryId; ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="category">Category Name:</label>
                    <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($category['category_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="code">Category Code:</label>
                    <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($category['category_code']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="sex">Sex:</label>
                    <select id="sex" name="sex" required>
                        <option value="Male" <?php echo ($category['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($category['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($category['age']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="weight">Weight (kg):</label>
                    <input type="number" id=" weight" name="weight" value="<?php echo htmlspecialchars($category['weight']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="image">Upload New Image (Optional):</label>
                    <input type="file" name="image">
                    <?php if ($category['set_picture']): ?>
                        <p>Current Image:</p>
                        <img src="<?php echo htmlspecialchars($category['set_picture']); ?>" alt="Category Image" style="width: 100px; height: auto;">
                    <?php endif; ?>
                </div>

                <button type="submit" name="update_category">Update</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>