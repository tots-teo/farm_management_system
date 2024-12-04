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

    public function updateCategory($id, $categoryName, $categoryCode, $sex, $age, $weight) {
        $stmt = $this->conn->prepare("UPDATE categories SET category_name = ?, category_code = ?, sex = ?, age = ?, weight = ? WHERE id = ?");
        $stmt->bind_param("sssiid", $categoryName, $categoryCode, $sex, $age, $weight, $id);
        return $stmt->execute();
    }

    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function addCategory($categoryName, $categoryCode, $sex, $age, $weight) {
        $stmt = $this->conn->prepare("INSERT INTO categories (category_name, category_code, sex, age, weight) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssid", $categoryName, $categoryCode, $sex, $age, $weight);
        $stmt->execute();
    }

    public function getAllCategories($searchTerm = '') {
        $searchTerm = "%" . $searchTerm . "%";
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE category_name LIKE ? OR category_code LIKE ?");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Return categories as an associative array
    }
}
