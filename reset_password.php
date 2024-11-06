<?php
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

$message = "";
$valid_token = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token exists in database
    $sql = "SELECT * FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $valid_token = true;
    } else {
        $message = "Invalid or expired reset token.";
    }
}

if (isset($_POST['update_password'])) {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password and remove reset token
        $update_sql = "UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $hashed_password, $token);
        
        if ($update_stmt->execute()) {
            $message = "Password updated successfully! You can now <a href='login.php'>login</a> with your new password.";
        } else {
            $message = "Error updating password.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMT Farm Management - Reset Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .message {
            margin: 20px 0;
            padding: 10px;
            border radius: 5px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input[type="password"], input[type="submit"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
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
            Reset Your Password
        </div>

        <div class="form-container">
            <?php if ($message): ?>
                <div class="message">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($valid_token): ?>
                <form action="reset_password.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <input type="password" name="new_password" placeholder="New Password" required>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <input type="submit" name="update_password" value="Update Password">
                </form>
            <?php else: ?>
                <p>Please provide a valid reset token to reset your password.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>