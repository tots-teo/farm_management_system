<?php
class Livestock {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function addCategory($categoryName, $categoryCode) {
        $stmt = $this->conn->prepare("INSERT INTO categories (category_name, category_code, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $categoryName, $categoryCode);
        $stmt->execute();
    }

    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function getAllCategories($search = '') {
        $search = '%' . $this->conn->real_escape_string($search) . '%'; // Escape input to prevent SQL injection
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE category_name LIKE ? OR category_code LIKE ?");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}