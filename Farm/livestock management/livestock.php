<?php
class Livestock {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    public function viewPicture($id) {
        $stmt = $this->conn->prepare("SELECT set_picture FROM categories WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute(); 
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['set_picture'];
    }

    public function addCategory($categoryName, $categoryCode) {
        $stmt = $this->conn->prepare("INSERT INTO categories (category_name, category_code) VALUES (?, ?)");
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