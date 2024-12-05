<?php
include('Database.php');
include('Animal.php');

$db = new Database('localhost', 'root', '', 'FoodInventory');
$animalObj = new Animal($db->getConnection());

if (isset($_GET['animal'])) {
    $animal = $_GET['animal'];
    $result = $animalObj->getBrandsByAnimal($animal);

    if ($result->num_rows > 0) {
        echo "<h3>Select a Brand:</h3>";
        while ($row = $result->fetch_assoc()) {
            echo "<button class='btn btn-info' onclick='fetchFeedVarieties(\"" . $row['brand'] . "\")'>" . $row['brand'] . "</button>";
        }
    } else {
        echo "No brands found for this animal.";
    }
}
?>