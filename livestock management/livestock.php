<?php
class Livestock {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Function to add a category
    public function addCategory($category, $code) {
        $stmt = $this->conn->prepare("INSERT INTO categories (category_name, category_code) VALUES (?, ?)");
        $stmt->bind_param("ss", $category, $code);
        $stmt->execute();
        $stmt->close();
    }

    // Function to delete a category
    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    // Function to get all categories
    public function getAllCategories() {
        $result = $this->conn->query("SELECT * FROM categories");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}