<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login page/login.php");
    exit();
}

// Include the livestock management file, database connection, and error handling class
include 'livestock.php';
include '../db.php';
include '../classes/CategoryManager.php';

// Create an instance of Livestock
$livestockManager = new Livestock($conn);
$categoryManager = new CategoryManager($conn);

// Handle form submission for adding category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $category = $_POST['category'];
        $code = $_POST['code'];

        switch ($category) {
            case 'Pig':
                $code = 'PG';
                break;
            case 'Cow':
                $code = 'CW';
                break;
            case 'Chicken':
                $code = 'CH';
                break;
            case 'Goat':
                $code = 'GT';
                break;
        }       
        // Add category to the database
        $livestockManager->addCategory($category, $code);
    
        $fileName = $_FILES['image']['name']; 
        $tempName = $_FILES['image']['tmp_name'];
        
        $uniqueFileName = uniqid() . '_' . $fileName; 
        $folder = '../assets/adminPicture/'.$uniqueFileName;
        
        $path = '../assets/adminPicture/'; 
        $storePath = $path . $uniqueFileName;
        
        $stmt = $conn->prepare("UPDATE categories SET set_picture = ?");
        $stmt->bind_param('s', $storePath);
        $stmt->execute();

        if (move_uploaded_file($tempName, $folder)) {
            echo "File uploaded successfully.";
        } else {
            echo "File upload failed.";
        }
        
        header("Location: livestock_management.php"); // Redirect to avoid resubmission
        exit();
    }
}

// Handle updating category
if (isset($_POST['update_category'])) {
    $id = $_POST['id'];
    $categoryName = $_POST['category_name'];
    $categoryCode = $_POST['category_code'];
    $categoryManager->updateCategory($id, $categoryName, $categoryCode);
    header("Location: livestock_management.php"); // Redirect after updating
    exit();
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

// Fetch category data for modal if view_id is set
$modalCategory = null;
if (isset($_GET['view_id'])) {
    $modalCategory = $categoryManager->getCategoryById($_GET['view_id']);
}
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
                <form method="POST" action="livestock_management.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category" required>
                            <option value="" disabled selected>Select an animal</option>
                            <option value="Pig">Pig</option>
                            <option value="Cow">Cow</option>
                            <option value="Chicken">Chicken</option>
                            <option value="Goat">Goat</option>
                        </select>
                    </div>
                    <input type="file" name="image" required>
                    <button type="submit" name="add_category">Save</button>
                </form>
            </div>

            <h3>Registered Categories</h3>

            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Search categories" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit" title="Search"><i class="fas fa-search"></i> Search</button>
                </form>
            </div>

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
                            <td><?php echo htmlspecialchars($category['category_code']) . ($category['id']); ?></td>
                            <td><?php echo htmlspecialchars($category['created_at']); ?></td>
                            <td>
                                <a href="livestock_management.php?view_id=<?php echo $category['id']; ?>" class="open-modal" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="livestock_management.php?id=<?php echo $category['id']; ?>" class="open-modal" title="Update">
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

    <div id="modal" class="modal" style="display: <?php echo isset($_GET['view_id']) ? 'block' : 'none'; ?>;">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='livestock_management.php'">&times;</span>
            <div id="modal-body">
                <?php if ($modalCategory): ?>
                    <img src="<?php echo htmlspecialchars($modalCategory['set_picture']); ?>" alt="Category Image" style="width: 100%; height: auto;">
                    <h3><?php echo htmlspecialchars($modalCategory['category_name']); ?></h3>
                    <p>Category Code: <?php echo htmlspecialchars($modalCategory['category_code']); ?></p>
                <?php else: ?>
                    <p>Category not found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="updateModal" class="modal" style="display: <?php echo isset($_GET['update_id']) ? 'block' : 'none'; ?>;">
        <div class="modal-content">
            <span class="close-btn" onclick="window.location.href='livestock_management.php'">&times;</span>
            <div id="modal-body">
                <?php if ($modalCategory): ?>
                    <h3>Update Category</h3>
                    <form method="POST" action="livestock_management.php">
                        <input type="hidden" name="id" value="<?php echo $modalCategory['id']; ?>">
                        <label for="category_name">Category Name:</label>
                        <input type="text" id="category_name" name="category_name" value="<?php echo htmlspecialchars($modalCategory['category_name']); ?>" required>
                        
                        <label for="category_code">Category Code:</label>
                        <input type="text" id="category_code" name="category_code" value="<?php echo htmlspecialchars($modalCategory['category_code']); ?>" required>
                        
                        <button type="submit" name="update_category">Update Category</button>
                    </form>
                <?php else: ?>
                    <p>Category not found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>