<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login_page/login.php");
    exit();
}

// Include necessary files
include '../db.php';
include '../Sidebar/sidebar.php'; // Sidebar for navigation
include 'CropMethods.php'; // Include the CropMethods class

// Create Sidebar instance
$role = $_SESSION['role'];
$sidebar = new Sidebar($role);

// Create an instance of CropMethods
$cropMethods = new CropMethods();

// Define the crops
$crops = [
    'pechay' => 'Pechay',
    'tomatoes' => 'Tomatoes',
    'rice' => 'Rice',
    'eggplant' => 'Eggplant',
    'banana' => 'Banana',
    'pineapples' => 'Pineapples',
    'coconut' => 'Coconut',
    'squash' => 'Squash',
    'winged bean' => 'Winged Bean',
];

// Handle form submission for adding a crop
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_crop'])) {
        $cropName = $_POST['crop_name'];
        $quantity = $_POST['quantity'];

        // Insert crop into the database
        $cropMethods->addCrop($cropName, $quantity);
    }
}

// Handle crop deletion
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $cropMethods->deleteCrop($deleteId);
}

// Fetch all crops
$cropsData = $cropMethods->getAllCrops();

$sidebar->render();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Management</title>
    <link rel="stylesheet" href="../Design/crops.css"> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="main-content">
    <div class="container">
        <h2>Crop Management</h2>

        <!-- Crop Registration Form -->
        <div class="form-container">
            <form method="POST" action="../Crop Management/CropManagement.php">
                <div class="form-group">
                    <label for="crop_name">Crop Name:</label>
                    <select id="crop_name" name="crop_name" required>
                        <option value="" disabled selected>Select a crop</option>
                        <?php foreach ($crops as $cropCode => $cropName): ?>
                            <option value="<?php echo $cropName; ?>"><?php echo $cropName; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="1" required>
                </div>

                <button type="submit" name="add_crop">Add Crop</button>
            </form>
        </div>

        <!-- Registered Crops Table -->
        <h3>Registered Crops</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Crop Name</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($cropsData)): ?>
                <?php foreach ($cropsData as $index => $crop): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($crop['crop_name']); ?></td>
                        <td><?php echo htmlspecialchars($crop['quantity']); ?></td>
                        <td>
                            <a href="view_crop.php?id=<?php echo $crop['id']; ?>" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="update_crop.php?id=<?php echo $crop['id']; ?>" title="Update">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?delete_id=<?php echo $crop['id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this crop?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No crops found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>