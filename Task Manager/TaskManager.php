<?php
class TaskManager {
    private $conn;

    // Constructor to initialize database connection
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Method to create a new task
    public function createTask($task_name, $status, $due_date) {
        $sql = "INSERT INTO task (task_name, status, due_date) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $task_name, $status, $due_date);
        return $stmt->execute();
    }

    // Method to fetch all tasks
    public function fetchAllTasks() {
        $sql = "SELECT * FROM task";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch all tasks as an associative array
    }

    // Method to update a task
    public function updateTask($id, $task_name, $status, $due_date) {
        $sql = "UPDATE task SET task_name = ?, status = ?, due_date = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $task_name, $status, $due_date, $id);
        return $stmt->execute();
    }

    // Method to delete a task
    public function deleteTask($id) {
        $sql = "DELETE FROM task WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>