<?php
require_once '../db.php';
require_once '../classes/user.php';
require_once '../classes/validator.php';
require_once '../classes/ErrorHandler.php';
require_once '../classes/Database.php';

class Registration {
    private $database;
    private $conn;
    private $validator;
    private $errorHandler;

    public function __construct() {
        // Update the database connection parameters
        $this->database = new Database('localhost:3307', 'root', '', 'farm_management'); // Use 'root' and '' for XAMPP
        $this->conn = $this->database->getConnection();
        $this->validator = new Validator();
        $this->errorHandler = new ErrorHandler();
    }

    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $gender = $_POST['gender'];
            $role = $_POST['role'];

            if (!$this->validator->validateEmail($email)) {
                $this->errorHandler->setError("Invalid email format.");
            } elseif (!$this->validator->validatePassword($password)) {
                $this->errorHandler->setError("Password must be at least 6 characters.");
            } else {
                $user = new User($this->conn);
                $registrationResult = $user->register($firstName, $lastName, $email, $password, $phone, $gender, $role);
                if ($registrationResult === true) {
                    header("Location: login.php?registered=1");
                    exit();
                } else {
                    $this->errorHandler->setError($registrationResult);
                }
            }
        }
    }

    public function displayForm() {
        ?>
        <form method="POST" action="">
            <input type="text" name="first_name" required placeholder="First Name">
            <input type="text" name="last_name" required placeholder="Last Name">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Password">
            <input type="text" name="phone" required placeholder="Phone">
            <select name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <select name="role" required>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <?php
        $this->errorHandler->displayError();
    }
}

// Initialize Registration
$registration = new Registration();
$registration->handleFormSubmission();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MJCK Farm Management - Register</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../Design/register.css">
</head>
<body>
    <header>
        
    </header>

    <div class="container">
        <div class="welcome">Create Your Account</div>
        <div class="form-container">
            <?php $registration->displayForm(); ?>

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
            
        </div>
    </div>
</body>
</html>