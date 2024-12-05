<?php
include('Database.php');
include('Feed.php');

// Initialize database and feed object
$db = new Database('localhost', 'root', '', 'FoodInventory');
$feedObj = new Feed($db->getConnection());

if (isset($_POST['feed_id']) && isset($_POST['quantity'])) {
    $feedId = (int) $_POST['feed_id'];
    $quantity = (int) $_POST['quantity'];

    // Check if the quantity to be sold is greater than the available stock
    $query = "SELECT stock FROM feeds WHERE feed_id = ?";
    $stmt = $db->getConnection()->prepare($query);
    $stmt->bind_param('i', $feedId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['stock'] < $quantity) {
            echo "Error: Not enough stock to sell.";
        } else {
            // Call the updateStock method for selling
            echo $feedObj->updateStock($feedId, $quantity, 'sell');
        }
    } else {
        echo "Feed not found.";
    }
} else {
    echo "Invalid input.";
}
?>