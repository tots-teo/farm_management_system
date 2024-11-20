<?php
include '../db.php';

class SessionManager {
    public function __construct() {
        $this->startSession();  // Automatically start session
    }

    public function startSession() {
        session_start();
    }

    public function destroySession() {
        session_destroy();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getUserRole() {
        return $_SESSION['role'] ?? null; // Safe access using null coalescing operator
    }

    public function setUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['role'] = $user['role'];
    }
}
?>
