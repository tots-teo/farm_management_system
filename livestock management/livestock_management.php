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
include 'livestock.php';
include '../db.php';
include '../classes/CategoryManager.php';
include '../Sidebar/sidebar.php';

// Create instances
$livestockManager = new Livestock($conn);
$categoryManager = new CategoryManager($conn);
$sidebar = new Sidebar($role);

// Define valid codes for each category
$validCodes = [
    'Pig' => 'PG',
    'Cow' => 'CW',
    'Chicken' => 'CH',
    'Goat' => 'GT'
];

$errorMessage = '';

// Handle form submission for adding category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $category = $_POST['category'];
        $code = $_POST['code'];

        // Validate category code
        if ($validCodes[$category] !== $code) {
            $errorMessage = "Error: The category code for '$category' must be '{$validCodes[$category]}'.";
        } else {
            // Add category to the database
            $livestockManager->addCategory($category, $code);

            // Redirect after successful submission
            header("Location: livestock_management.php");
            exit();
        }
    }
}

// Delete category logic
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    // Delete category from the database
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param('i', $deleteId);

    if ($stmt->execute()) {
        echo "Category deleted successfully.";
    } else {
        echo "Error deleting category.";
    }

    // Redirect to avoid resubmission
    header("Location: livestock_management.php");
    exit();
}

// Fetch category data with search feature
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$categoryData = $livestockManager->getAllCategories($searchTerm);

$sidebar->render();
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

<div class="main-content">
    <div class="container">
        <h2>Category Registration</h2>

        <!-- Category Registration Form -->
        <div class="form-container">
            <form method="POST" action="livestock_management.php">
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

                <div class="form-group">
                    <label for="code">Category Code:</label>
                    <input type="text" id="code" name="code" required>
                </div>

                <!-- Display error message if any -->
                <?php if ($errorMessage): ?>
                    <div class="error-message">
                        <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
                    </div>
                <?php endif; ?>

                <button type="submit" name="add_category">Save</button>
            </form>
        </div>

        <!-- Registered Categories Table -->
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

</body>
</html>
