<?php
include '../db.php';

class EmailHandler {
    public function sendEmail($to, $subject, $message) {
        $headers = "From: no-reply@yourdomain.com\r\n"; // Replace with your domain email
        return mail($to, $subject, $message, $headers);
    }
}

$message = "";
if (isset($_POST['reset'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50)); // Generate a random token
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send email with reset link
        $emailHandler = new EmailHandler();
        $to = $email; // User's email
        $subject = "Password Reset Request";
        $resetLink = "localhost/finalphp/Farm/login/reset_password.php?token=" . $token; // Update with your domain
        $messageBody = "You have requested to reset your password.\n\n" .
                       "Please copy the link below to reset your password:\n" .
                       $resetLink . "\n\n" .
                       "If you did not request this, please ignore this email.";

        if ($emailHandler->sendEmail($to, $subject, $messageBody)) {
            $message = "A password reset email has been sent to your email address.";
        } else {
            $message = "Failed to send the email.";
        }
    } else {
        $message = "No account found with that email address.";
    }
}
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
    <header></header>

    <div class="container">
        <div class="welcome">Forgot Your Password?</div>

        <div class="form-container">
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
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