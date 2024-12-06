<?php
include '../db.php'; // Corrected the file name from db.ph to db.php

class ErrorHandler {
    private $errorMessage;

    public function setError($message) {
        $this->errorMessage = $message;
    }

    public function getError() {
        return $this->errorMessage;
    }

    public function displayError() {
        // Improved error display with HTML escaping and additional check
        if (!empty($this->errorMessage)) { // Check for an empty error message
            echo "<div class='error-message'>" . htmlspecialchars($this->errorMessage, ENT_QUOTES, 'UTF-8') . "</div>"; // Escape HTML
        }
    }
}
?>
