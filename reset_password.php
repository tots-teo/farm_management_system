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

// Add reset_token column if it doesn't exist
$check_column = "SHOW COLUMNS FROM users LIKE 'reset_token'";
$result = $conn->query($check_column);

if ($result->num_rows == 0) {
    $alter_table = "ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) DEFAULT NULL";
    if ($conn->query($alter_table) === TRUE) {
        echo "Reset token column added successfully";
    } else {
        echo "Error adding reset token column: " . $conn->error;
    }
}

$message = "";

if (isset($_POST['reset'])) {
    $email = $_POST['email'];

    // Prepare statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a random token
        $token = bin2hex(random_bytes(32));

        // Update the user's reset token
        $update_sql = "UPDATE users SET reset_token = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $token, $email);

        if ($update_stmt->execute()) {
            // In a real application, you would send an email here
            $reset_link = "http://localhost/FinalPHP/reset_password.php?token=" . $token;

            // For demonstration purposes, we'll just show the link
            $message = "Password reset link (for demonstration): <br>" . $reset_link;

            // In production, you would use something like:
            // mail($email, "Password Reset", "Click this link to reset your password: " . $reset_link);
            // $message = "A password reset link has been sent to your email.";
        } else {
            $message = "Error updating reset token.";
        }
    } else {
        $message = "No account found with that email address.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MJCK Farm Management - Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            word-wrap: break-word;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input[type="email"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            color: #4CAF50;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
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
            Forgot Your Password?
        </div>

        <div class="form-container">
            <?php if ($message): ?>
                <div class="message">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="forgot_password.php" method="POST">
                <h2>Reset Your Password</h2>
                <p>Enter your email address and we'll send you a link to reset your password.</p>
                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="submit" name="reset" value="Reset Password">

                <div class="login-link">
                    <p>Remember your password? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>