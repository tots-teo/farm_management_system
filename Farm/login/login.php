<?php
//INCLUDE FILES
require_once '../db.php';
require_once '../Methods/user.php';
require_once '../Methods/sessionManager.php';
require_once '../Methods/Database.php';

//CREATE INTANTIATION
$sessionManager = new SessionManager();
$registration_success = null; 
$error_message = null;

$database = new Database('localhost:3307', 'root', '', 'farm_management');
$conn = $database->getConnection(); //GET THE DB CONNECTION

// HANDLE LOGIN FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Pass the database connection to the User class
    $user = new User($conn); //CREATE USER INSTANTIATION
    $loggedInUser   = $user->login($email, $password); //ATTEMPT TO LOGIN USER
    if ($loggedInUser ) { //IF USER IS LOGGED IN
        $sessionManager->setUserSession($loggedInUser ); //REDIRECT NA SA DASHBOARD
        header("Location: ../Dashboard/dashboard.php");
        exit();
    } else {
        $error_message = "Invalid email or password."; //MALI CREDENTIALS
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