<?php
require_once 'Database.php';
require_once 'FeedController.php';

$db = new Database('localhost', 'root', '', 'FoodInventory');
$feedController = new FeedController($db->getConnection());

// Fetch all feeds with their current stock
$feeds = $feedController->getFeeds();

if (count($feeds) > 0) {
    echo "<h3>Current Stock:</h3>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>Feed Name</th><th>Brand</th><th>Stock</th></tr></thead>";
    echo "<tbody>";
    foreach ($feeds as $feed) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($feed['name']) . "</td>";
        echo "<td>" . htmlspecialchars($feed['brand']) . "</td>";
        echo "<td>" . htmlspecialchars($feed['stock']) . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No feeds available in stock.</p>";
}
?>