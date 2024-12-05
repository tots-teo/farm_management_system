<?php
include '../db.php'; // Include the database connection

class CropMethods {
    public function addCrop($cropName, $quantity) {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO crops (crop_name, quantity) VALUES (?, ?)");
        $stmt->bind_param("si", $cropName, $quantity);
        $stmt->execute();
    }

    public function deleteCrop($id) {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM crops WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public function getAllCrops() {
        global $conn;

        $result = $conn->query("SELECT * FROM crops");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}