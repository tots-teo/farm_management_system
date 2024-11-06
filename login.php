<?php
session_start();

// Database connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "farm_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            switch($user['role']) {
                case 'Admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'Employee':
                    header("Location: employee_dashboard.php");
                    break;
                case 'User':
                    header("Location: user_dashboard.php");
                    break;
            }
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "User not found!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMT Farm Management - Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-message {
            color: #ff0000;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#">HOME</a></li>
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
                
                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
                
                <div class="register-link">
                    <p>Don't have an account? <a href="index.php">Register here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>