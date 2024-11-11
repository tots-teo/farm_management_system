<?php
// Database connection (adjust credentials as needed)
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

if (isset($_POST['register'])) {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $conn->real_escape_string($_POST['gender']);
    $role = $_POST['role'];

    // Ensure the role is Admin
    if ($role !== 'Admin') {
        $error_message = "Only admin registration is allowed!";
    } else {
        // Check if passwords match
        if ($password !== $confirm_password) {
            $error_message = "Passwords do not match!";
        } else {
            // Check if email already exists
            $check_email = "SELECT * FROM users WHERE email = '$email'";
            $result = $conn->query($check_email);
            
            if ($result->num_rows > 0) {
                $error_message = "Email already exists! Please use a different email.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert data into the database
                $sql = "INSERT INTO users (first_name, last_name, email, phone, password, gender, role)
                        VALUES ('$first_name', '$last_name', '$email', '$phone', '$hashed_password', '$gender', '$role')";

                if ($conn->query($sql) === TRUE) {
                    // Redirect to login page with success message
                    header("Location: login.php?registered=1");
                    exit();
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                }
            }
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
    <title>MJCK Farm Management - Admin Registration</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-message {
            color: #ff0000;
            margin-bottom: 10px;
            text-align: center;
            padding: 10px;
            background-color: rgba(255, 0, 0, 0.1);
            border-radius: 5px;
        }
        .nav-links {
            text-align: center;
            margin-top: 15px;
        }
        .nav-links a {
            margin: 0 10px;
            color: #4CAF50;
            text-decoration: none;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50; /* Button color */
            color: white; /* Text color */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer on hover */
        }
        input[type="submit"]:hover {
            background-color: #45a049; /* Darker color on hover */
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
            Welcome to MJCK Farm
        </div>

        <div class="form-container">
            <?php if ($error_message): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" id="registrationForm">
                <h2>Register as Admin</h2>
                <input type="text" name="first_name" placeholder ="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                <div class="gender-selection">
                    <label>
                        <input type="radio" name="gender" value="Male" required> Male
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Female" required> Female
                    </label>
                </div>
                <input type="hidden" name="role" value="Admin">
                <input type="submit" name="register" value="Register">
            </form>

            <div class="nav-links">
                <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</body>
</html>