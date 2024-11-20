<?php
require_once '../db.php';
require_once '../classes/user.php';
require_once '../classes/sessionManager.php';

// Create a single instance of SessionManager which will handle session start
$sessionManager = new SessionManager();

class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Create a database connection
$database = new Database('localhost:3307', 'root', '', 'farm_management');
$conn = $database->getConnection();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Pass the database connection to the User class
    $user = new User($conn);
    $loggedInUser  = $user->login($email, $password);
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
        <div class="welcome">Welcome to MJCK Farm</div>
        <div class="form-container">
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <h2>Login to Your Account</h2>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
                <div class="account-message">
                    <p>Don't have an account? <a href="register.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>