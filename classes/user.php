<?php
include '../db.php';

class User {
    private $conn;

    public function __construct($databaseConnection) {
        $this->conn = $databaseConnection;
    }

    // Register a new user
    public function register($firstName, $lastName, $email, $password, $phone, $gender, $role) {
        // Check if email already exists
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return "Email already exists!";
        }

        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $this->conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password, gender, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $firstName, $lastName, $email, $phone, $hashedPassword, $gender, $role);
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Login user
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user; // Return user data
            }
        }
        return false; // Invalid credentials
    }

    // Fetch user data by ID
    public function getUserById($userId) {
        $stmt = $this->conn->prepare("SELECT id, first_name, last_name, email, phone, gender, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Fetch and return user data
    }

    // Update user profile (without password)
    public function updateUser($userId, $firstName, $lastName, $email, $phone, $gender) {
        $stmt = $this->conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, gender = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $firstName, $lastName, $email, $phone, $gender, $userId);
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error updating profile: " . $this->conn->error;
        }
    }

    // Update user password
    public function updatePassword($userId, $currentPassword, $newPassword) {
        // Fetch current password
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify current password
        if (password_verify($currentPassword, $user['password'])) {
            // Hash new password and update
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $hashedPassword, $userId);
            if ($updateStmt->execute()) {
                return true;
            } else {
                return "Error updating password: " . $this->conn->error;
            }
        } else {
            return "Current password is incorrect!";
        }
    }
}
?>
