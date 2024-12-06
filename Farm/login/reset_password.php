<?php
include '../db.php';

class EmailHandler {
    public function sendEmail($to, $subject, $message) {
        $headers = "From: no-reply@yourdomain.com\r\n"; // Replace with your domain email
        return mail($to, $subject, $message, $headers);
    }
}

$message = "";
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid
    $stmt = $conn->prepare("SELECT email FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, allow password reset
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            $email = $result->fetch_assoc()['email'];

            // Check if the new password and confirm password match
            if ($newPassword !== $confirmPassword) {
                $message = "Passwords do not match. Please try again.";
            } else {
                // Update the password in the database
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE email = ?");
                $update_stmt->bind_param("ss", $hashedPassword, $email);

                if ($update_stmt->execute()) {
                    // Send confirmation email
                    $emailHandler = new EmailHandler();
                    $subject = "Your Password Has Been Reset";
                    $messageBody = "Your password has been successfully reset.\n\n" .
                                   "If you did not request this change, please contact support at mjckfarm@gmail.com.";

                    if ($emailHandler->sendEmail($email, $subject, $messageBody)) {
                        $message = "Your password has been reset successfully. A confirmation email has been sent.";
                    } else {
                        $message = "Your password has been reset successfully, but failed to send confirmation email.";
                    }
                } else {
                    $message = "Error updating password.";
                }
            }
        }
    } else {
        $message = "Invalid token. Please request a new password reset.";
    }
} else {
    $message = "No token provided. Please request a password reset.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MJCK Farm Management - Reset Password</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header></header>

    <div class="container">
        <div class="welcome">Reset Your Password</div>

        <div class="form-container">
            <?php if ($message): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                <h2>Enter New Password</h2>
                <input type="password" name="new_password" placeholder="Enter new password" required>
                <input type="password" name="confirm_password" placeholder="Confirm new password" required>
                <input type="submit" value="Reset Password">
                
                <div class="login-link">
                    <p>Remember your password? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>