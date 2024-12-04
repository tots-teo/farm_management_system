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

        // New method to get the number of tasks
    public function getNumberOfTasks() {
        $stmt = "SELECT COUNT(*) AS count FROM task"; // Replace 'task' with your actual table name
        $result = $this->db->query($stmt);
        $row = $result->fetch_assoc();
        return $row['count'];
    }
}
?>