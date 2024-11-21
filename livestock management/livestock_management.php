<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login page/login.php");
    exit();
}

// Include the livestock management file
include 'livestock.php';
include '../db.php';

// Now you can access the livestock data
$livestockManager = new Livestock($conn);
$livestockData = $livestockManager->getAllLivestock();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['animals'])) {
        $selectedAnimals = $_POST['animals'];
        // Process the selected animals as needed
        echo "You selected: " . implode(", ", $selectedAnimals);
    } else {
        echo "No animals selected.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livestock Management</title>
    <link rel="stylesheet" href="../Design/dashboard.css">
</head>
<body>
    <h2>Livestock Management</h2>
    
    <!-- Multiple selection dropdown -->
    <form method="POST" action="">
        <label for="animals">Select Animals:</label>
        <select id="animals" name="animals[]" multiple>
            <option value="pig">Pig</option>
            <option value="cow">Cow</option>
            <option value="goat">Goat</option>
            <option value="chicken">Chicken</option>
        </select>
        <input type="submit" value="Submit">
    </form>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Age</th>
            <th>Action</th>
        </tr>
        <?php foreach ($livestockData as $livestock): ?>
        <tr>
            <td><?php echo htmlspecialchars($livestock['id']); ?></td>
            <td><?php echo htmlspecialchars($livestock['name']); ?></td>
            <td><?php echo htmlspecialchars($livestock['type']); ?></td>
            <td><?php echo htmlspecialchars($livestock['age']); ?></td>
            <td>
                <a href="edit_livestock.php?id=<?php echo $livestock['id']; ?>">Edit</a>
                <a href="delete_livestock.php?id=<?php echo $livestock['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>