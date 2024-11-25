<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login page/login.php");
    exit();
}

// Include the livestock management file and database connection
include 'livestock.php';
include '../db.php';

// Create an instance of Livestock
$livestockManager = new Livestock($conn);

// Handle form submission for adding category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $category = $_POST['category'];
        $code = $_POST['code'];

        // Add category to the database
        $livestockManager->addCategory($category, $code);
        header("Location: livestock_management.php"); // Redirect to avoid resubmission
        exit();
    }
}

// Handle deletion of category
if (isset($_GET['delete_id'])) {
    $livestockManager->deleteCategory($_GET['delete_id']);
    header("Location: livestock_management.php"); // Redirect after deletion
    exit();
}

// Fetch all category data with optional search
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$categoryData = $livestockManager->getAllCategories($searchTerm);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Registration</title>
    <link rel="stylesheet" href="../Design/livestock.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <a href="../crop_management.php">
            <img src="../assets/crop.png" alt="Crops">Crop Management
        </a>
        <a href="../feed_inventory.php">
            <img src="../assets/feed_inventory.png" alt="Feed Inventory">Feed Inventory
        </a>
        <a href="../task_manager.php">
            <img src="../assets/task.png" alt="Task Manager">Task Manager
        </a>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
            <a href="../admin_panel.php">
                <img src="../assets/farmer.png" alt="Admin Panel">Admin Panel
            </a>
        <?php endif; ?>
        <a href="../dashboard/logout.php" class="logout-btn">
            <img src="../assets/logout.png" alt="Logout">Logout
        </a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2>Category Registration</h2>
            
            <div class="form-container">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <input type="text" id="category" name="category" required>
                    </div>
                    <div class="form-group">
                        <label for="code">Code:</label>
                        <input type="text" id="code" name="code" required>
                    </div>
                    <button type="submit" name="add_category">Save</button>
                </form>
            </div>

            <h3>Registered Categories</h3>

            <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search categories" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" title="Search"><i class="fas fa-search"></i> Search</button>
            </form>

            <table>
                <tr>
                    <th>No</th>
                    <th>Category</th>
                    <th>Category Code</th>
                    <th>Posting Date</th>
                    <th>Action</th>
                </tr>
                <?php if (!empty($categoryData)): ?>
                    <?php foreach ($categoryData as $index => $category): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($category['category_code']); ?></td>
                            <td><?php echo htmlspecialchars($category['created_at']); ?></td>
                            <td>
                                <a href="view_category.php?id=<?php echo $category['id']; ?>" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="update_category.php?id=<?php echo $category['id']; ?>" title="Update">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete_id=<?php echo $category['id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this category?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <!-- Other content -->
</body>
</html>