<?php
// Include the database connection
include '../db.php';

class Livestock {
    private $conn;

    public function __construct($databaseConnection) {
        $this->conn = $databaseConnection;
    }

    public function getAllLivestock() {
        $stmt = $this->conn->prepare("SELECT * FROM livestock"); // Assuming you have a livestock table
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Add more methods as needed for managing livestock
}

// Usage example
$livestockManager = new Livestock($conn);
$livestockData = $livestockManager->getAllLivestock();
?>