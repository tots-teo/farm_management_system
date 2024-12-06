<?php
class FeedManager {
    private $conn;

    // Constructor to initialize database connection
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Method to add a new feed
    public function addFeed($feedName, $quantity) {
        $stmt = $this->conn->prepare("INSERT INTO feeds (feed_name, quantity) VALUES (?, ?)");
        $stmt->bind_param("si", $feedName, $quantity);
        return $stmt->execute();
    }

    // Method to fetch all feeds
    public function getAllFeeds($searchTerm = '') {
        $searchTerm = "%" . $this->conn->real_escape_string($searchTerm) . "%"; // Escape input to prevent SQL injection
        $stmt = $this->conn->prepare("SELECT * FROM feeds WHERE feed_name LIKE ?");
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Return feeds as an associative array
    }

    // Method to fetch a specific feed by ID
    public function getFeedById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM feeds WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Return feed data
    }

    // Method to update a feed
    public function updateFeed($id, $feedName, $quantity) {
        $stmt = $this->conn->prepare("UPDATE feeds SET feed_name = ?, quantity = ? WHERE id = ?");
        $stmt->bind_param("sii", $feedName, $quantity, $id);
        return $stmt->execute();
    }

    // Method to delete a feed
    public function deleteFeed($id) {
        $stmt = $this->conn->prepare("DELETE FROM feeds WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Method to get feed types based on feed name
    public function getFeedTypes($feedName) {
        $feedTypes = [
            'Pig' => ['Pre-Starter', 'Starter', 'Grower', 'Breeder'],
            'Chicken' => ['Integra 1000', 'Integra 2000', 'Integra 2500', 'Integra 3000'],
            'Cow' => ['Cattle Grower', 'Dairy Cattle', 'Hay'],
            'Goat' => ['Goat 16', 'Hay']
        ];

        return $feedTypes[$feedName] ?? []; // Return an empty array if feed name not found
    }
}
?>