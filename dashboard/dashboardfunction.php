<?php 
class DashboardStats {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function updateStats() {
        $stmt="SELECT COUNT(*) AS count FROM categories";
        $result = $this->db->query($stmt);
        $row = $result->fetch_assoc();
        $categories = $row['count'];
        return $categories;
        }

}
?>