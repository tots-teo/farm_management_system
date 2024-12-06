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

    // Method to get the number of admins
    public function getNumberOfAdmins() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin'"); // Use $this->db
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['admin_count'];
    }
}
?>