<?php
session_start();
include '../db.php';

$error_message = "";

if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
       
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            header("Location: ../dashboard/dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "User  not found!";
    }
}

$registration_success = "";
if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    $registration_success = "Registration successful! Please log in.";
}

$conn->close();
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
                
                <a href ="forgot_password.php" class="link-button">Forgot Password?</a>
                
                <div class="account-message">
                    <p>Don't have an account? <a href="register.php" class="link-button">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>