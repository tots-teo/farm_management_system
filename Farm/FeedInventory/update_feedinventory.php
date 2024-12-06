<?php
session_start();

// CHECK PO KUNG SI USER AY NAKA LOGIN
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login_page/login.php");
    exit();
}

// INITIALIZE ROLE
$role = $_SESSION['role'];

// INCLUDE FILES
include '../db.php';
include '../FeedInventory/FeedManager.php'; 
include '../Sidebar/sidebar.php';

// INSTANTIATION
$feedManager = new FeedManager($conn);
$sidebar = new Sidebar($role);

// FETCH FEED DETAILS FROM UPDATE FORM
$feedId = $_GET['id'] ?? null;
$feed = null;

if ($feedId) {
    $feed = $feedManager->getFeedById($feedId); 
    if (!$feed) {
        echo "Feed not found.";
        exit();
    }
}

// Handle form submission for updating feed
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_feed'])) {
        $feedName = $_POST['feed_name'];
        $quantity = $_POST['quantity'];

        // Update feed information in the database
        if ($feedManager->updateFeed($feedId, $feedName, $quantity)) {
            // Redirect after successful update
            header("Location: feed_inventory.php");
            exit();
        } else {
            $errorMessage = "Error updating feed.";
        }
    }
}

$sidebar->render();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Feed Inventory</title>
    <link rel="stylesheet" href="../Design/feed_inventory.css">
</head>
<body>

<div class="main-content">
    <div class="container">
        <h2>Update Feed Inventory</h2>

        <?php if (isset($errorMessage)): ?>
            <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="feed_name">Feed Name:</label>
                <input type="text" id="feed_name" name="feed_name" value="<?php echo htmlspecialchars($feed['feed_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity (kg):</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($feed['quantity']); ?>" required>
            </div>

            <button type="submit" name="update_feed">Update Feed</button>
        </form>

        <div class="back-link">
            <a href="feed_inventory.php">Back to Feed Inventory</a>
        </div>
    </div>
</div>

</body>
</html>