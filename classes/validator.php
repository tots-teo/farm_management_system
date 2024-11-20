<?php
include '../db.php';

class Validator {
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function validatePassword($password) {
        return strlen($password) >= 6; // Example validation
    }
}
?>