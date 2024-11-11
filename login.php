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
    $email = $conn->real_escape_string($_POST['email']);
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
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            // Redirect to dashboard for all roles
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "User  not found!";
    }
}

// Check for registration success message
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
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-message {
            color: #ff0000;
            margin-bottom: 10px;
            text-align: center;
        }
        .success-message {
            color: #008000;
            margin-bottom: 10px;
            text-align: center;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50; /* Green button color */
            color: white; /* Text color */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer on hover */
        }
        input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        .link-button {
            display: block; /* Make the link take the full width */
            color: #4CAF50; /* Green text color */
            text-decoration: none; /* No underline */
            margin-top: 10px; /* Spacing above links */
            text-align: center; /* Center the text */
        }
        .link-button:hover {
            text-decoration: underline; 
        }
        .account-message {
            text-align: center;
            margin-top: 10px; /* Spacing above the message */
        }
    </style>
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