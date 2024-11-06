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

if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $sql = "INSERT INTO users (first_name, last_name, email, phone, password, gender, role)
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$hashed_password', '$gender', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMT Farm Management - Register</title>
    <link rel="stylesheet" href="styles.css">
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
            <div class="role-selection">
                <button class="role-button active" onclick="showForm('User')">User</button>
                <button class="role-button" onclick="showForm('Employee')">Employee</button>
                <button class="role-button" onclick="showForm('Admin')">Admin</button>
            </div>

            <form action="register.php" method="POST" id="registrationForm">
                <h2>Register as <span id="roleTitle">User</span></h2>
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                <div class="gender-selection">
                    <label>
                        <input type="radio" name="gender" value="Male" checked> Male
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Female"> Female
                    </label>
                </div>

                <input type="hidden" name="role" id="roleInput" value="User">
                <input type="submit" name="register" value="Register">
                <p><a href="login.php">Already have an account?</a></p>
            </form>
        </div>
    </div>

    <script>
        function showForm(role) {
            document.getElementById('roleTitle').innerText = role;
            document.getElementById('roleInput').value = role;

            let buttons = document.querySelectorAll('.role-button');
            buttons.forEach(btn => btn.classList.remove('active'));
            
            event.target.classList.add('active');
        }
    </script>

</body>
</html>