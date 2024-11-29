<?php
include '../db.php'; // Include the database connection

class CategoryManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function viewCategory($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Existing methods...
    public function getCategoryById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Return the category data
    }

    public function updateCategory($id, $categoryName, $categoryCode) {
        $stmt = $this->conn->prepare("UPDATE categories SET category_name = ?, category_code = ? WHERE id = ?");
        $stmt->bind_param("ssi", $categoryName, $categoryCode, $id);
        return $stmt->execute();
    }

    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>