<?php
class Animal {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getBrandsByAnimal($animal) {
        $query = "SELECT DISTINCT f.brand FROM feeds f JOIN animals a ON a.feedId = f.feed_id WHERE a.name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $animal);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>