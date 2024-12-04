<?php
require_once '../db.php';
require_once '../classes/user.php';
require_once '../classes/sessionManager.php';
require_once '../classes/Database.php';

// Create a single instance of SessionManager which will handle session start
$sessionManager = new SessionManager();

// Initialize variables to avoid undefined variable warnings
$registration_success = null; // Initialize to null
$error_message = null; // Initialize to null

$database = new Database('localhost:3307', 'root', '', 'farm_management');
$conn = $database->getConnection();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Pass the database connection to the User class
    $user = new User($conn);
    $loggedInUser   = $user->login($email, $password);
    if ($loggedInUser ) {
        $sessionManager->setUserSession($loggedInUser );
        header("Location: ../dashboard/dashboard.php");
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MJCK Farm Management - Login</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">HOME</a></li>
                <li><a href="#">ABOUT US</a></li>
                <li><a href="#">CONTACT</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="welcome">
            Welcome Back to MJCK Farm
        </div>

        <div class="form-container">
            <?php if ($registration_success): ?>
                <div class="success-message">
                    <?php echo $registration_success; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <h2>Login to Your Account</h2>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" name="login" value="Login">
                
                <a href="forgot_password.php" class="link-button">Forgot Password?</a>
                
                <div class="account-message">
                    <p>Don't have an account? <a href="register.php" class="link-button">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>