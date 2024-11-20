<?php
require_once '../db.php';
require_once '../classes/user.php';
require_once '../classes/validator.php';
require_once '../classes/ErrorHandler.php';

$database = new Database('localhost', 'username', 'password', 'farm_management');
$conn = $database->getConnection();
$validator = new Validator();
$errorHandler = new ErrorHandler();

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];

    if (!$validator->validateEmail($email)) {
        $errorHandler->setError("Invalid email format.");
    } elseif (!$validator->validatePassword($password)) {
        $errorHandler->setError("Password must be at least 6 characters.");
    } else {
        $user = new User($conn);
        $registrationResult = $user->register($firstName, $lastName, $email, $password, $phone, $gender, $role);
        if ($registrationResult === true) {
            echo "Registration successful!";
        } else {
            $errorHandler->setError($registrationResult);
        }
    }
}
?>

<!-- HTML form for registration -->
<form method="POST" action="">
    <input type="text" name="first_name" required placeholder="First Name">
    <input type="text" name="last_name" required placeholder="Last Name">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <input type="text" name="phone" required placeholder="Phone">
    <select name="gender">
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select>
    <select name="role">
        <option value="user">User </option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit">Register</button>
</form>

<?php $errorHandler->displayError(); ?>