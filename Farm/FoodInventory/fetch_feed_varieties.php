<?php
include('Database.php');
include('Feed.php');

$db = new Database('localhost', 'root', '', 'FoodInventory');
$feedObj = new Feed($db->getConnection());

if (isset($_GET['brand'])) {
    $brand = $_GET['brand'];
    $result = $feedObj->getFeedsByBrand($brand);

    if ($result->num_rows > 0) {
        echo "<h3>Feed Varieties for " . $brand . ":</h3>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='feed-item'>";
            echo "<img src='images/" . $row['image'] . "' alt='" . $row['name'] . "'>";
            echo "<div>";
            echo "<h5>" . $row['name'] . "</h5>";
            echo "<p>Stock: <span id='stock-" . $row['feed_id'] . "'>" . $row['stock'] . "</span></p>";
            echo "<button class='btn btn-success' onclick='updateStock(" . $row['feed_id'] . ", \"restock\")'>Restock</button>";
            echo "<button class='btn btn-danger' onclick='updateStock(" . $row['feed_id'] . ", \"sell\")'>Sell</button>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "No feed varieties found for this brand.";
    }
}
?>