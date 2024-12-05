<?php
include('Database.php');
include('Feed.php');

// Initialize database and feed object
$db = new Database('localhost', 'root', '', 'FoodInventory'); 
$feedObj = new Feed($db->getConnection());

if (isset($_POST['feed_id']) && isset($_POST['quantity']) && isset($_POST['action'])) {
    $feedId = (int) $_POST['feed_id'];
    $quantity = (int) $_POST['quantity'];
    $action = $_POST['action'];  // either 'restock' or 'sell'

    // Validate quantity
    if ($quantity <= 0) {
        echo "Invalid quantity. Please enter a positive number.";
        exit();
    }

    // Call the updateStock method based on the action (restock or sell)
    if ($action == 'restock') {
        echo $feedObj->updateStock($feedId, $quantity, 'restock');
    } elseif ($action == 'sell') {
        echo $feedObj->updateStock($feedId, $quantity, 'sell');
    } else {
        echo "Invalid action.";
    }
} else {
    echo "Invalid input.";
}
?>