<?php
include '../db.php';

class PasswordReset {
    private $conn;

    public function __construct($databaseConnection) {
        $this->conn = $databaseConnection;
    }

    public function addResetTokenColumn() {
        $check_column = "SHOW COLUMNS FROM users LIKE 'reset_token'";
        $result = $this->conn->query($check_column);

        if ($result->num_rows == 0) {
            $alter_table = "ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) DEFAULT NULL";
            if ($this->conn->query($alter_table) === TRUE) {
                echo "Reset token column added successfully";
            } else {
                echo "Error adding reset token column: " . $this->conn->error;
            }
        }
    }

    public function requestPasswordReset($email) {
        // Prepare statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate a random token
            $token = bin2hex(random_bytes(32));
            // Update the user's reset token
            $update_sql = "UPDATE users SET reset_token = ? WHERE email = ?";
            $update_stmt = $this->conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $token, $email);

            if ($update_stmt->execute()) {
                $reset_link = "http://localhost/FinalPHP/reset_password.php?token=" . $token;
                return "Password reset link (for demonstration): <br>" . $reset_link;
            } else {
                return "Error updating reset token.";
            }
        } else {
            return "No account found with that email address.";
        }
    }
}

// Usage
$conn = new mysqli("localhost:3307", "root", "", "farm_management");
$passwordReset = new PasswordReset($conn);
$passwordReset->addResetTokenColumn();

$message = "";
if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $message = $passwordReset->requestPasswordReset($email);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MJCK Farm Management - Forgot Password</title>
    <link rel="stylesheet" href="../styles.css">
    
</head>
<body>
    <header>
        
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