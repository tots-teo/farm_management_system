<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login_page/login.php");
    exit();
}

// Include necessary files
include '../db.php';
include '../Sidebar/sidebar.php';
include 'CropMethods.php'; // Include the CropMethods class

// Create an instance of CropMethods
$cropMethods = new CropMethods();

// Fetch crop details for the update form
$cropId = $_GET['id'] ?? null;
$crop = null;

if ($cropId) {
    $stmt = $conn->prepare("SELECT * FROM crops WHERE id = ?");
    $stmt->bind_param('i', $cropId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $crop = $result->fetch_assoc();
    } else {
        echo "Crop not found.";
        exit();
    }
}

// Handle form submission for updating crop
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_crop'])) {
        $cropName = $_POST['crop_name'];
        $quantity = $_POST['quantity'];

        // Update crop in the database
        $stmt = $conn->prepare("UPDATE crops SET crop_name = ?, quantity = ? WHERE id = ?");
        $stmt->bind_param("sii", $cropName, $quantity, $cropId);
        $stmt->execute();

        // Redirect after updating
        header("Location: CropManagement.php");
        exit();
    }
}

$sidebar = new Sidebar($_SESSION['role']);
$sidebar->render();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Crop</title>
    <link rel="stylesheet" href="../Design/crops.css">
</head>
<body>

<div class="main-content">
    <div class="container">
        <h2>Update Crop</h2>

        <!-- Update Crop Form -->
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="crop_name">Crop Name:</label>
                    <input type="text" id="crop_name" name="crop_name" value="<?php echo htmlspecialchars($crop['crop_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($crop['quantity']); ?>" min="1" required>
                </div>

                <button type="submit" name="update_crop">Update Crop</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>