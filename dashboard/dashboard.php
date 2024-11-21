<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login page/login.php");
    exit();
}

// Check user role for access control
$role = $_SESSION['role'];

// Placeholder values for statistics (replace with actual database queries)
$numberOfLivestock = 150; // Example value
$numberOfAdmins = 3;      // Example value
$numberOfTasks = 5;       // Example value
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MJCK Farm Management - Dashboard</title>
    <link rel="stylesheet" href="../Design/dashboard.css">

</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../mjck farm.jpg" alt="MJCK Farm Logo">
            <h3>MJCK Farm</h3>
        </div>
        <a href="../livestock management/livestock_management.php">
            <img src="../assets/livestock.png" alt="Livestock">Livestock Management
        </a>
        <a href="crop_management.php">
            <img src="../assets/crop.png" alt="Crops">Crop Management
        </a>
        <a href="feed_inventory.php">
            <img src="../assets/feed_inventory.png" alt="Feed Inventory">Feed Inventory
        </a>
        <a href="task_manager.php">
            <img src="../assets/task.png" alt="Task Manager">Task Manager
        </a>
        <?php if ($role === 'Admin'): ?>
            <a href="admin_panel.php">
                <img src="../assets/farmer.png" alt="Admin Panel">Admin Panel
            </a>
        <?php endif; ?>
        <a href="logout.php" class="logout-btn">
            <img src="../assets/logout.png" alt="Logout">Logout
        </a>
    </div>

    <div class="main-content">
        <header>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></h2>
        </header>

    <div class="dashboard-overview">
        <div class="stats-box map-overview">
            <h4>Map Overview</h4>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31796.48949152984!2d121.3342235!3d13.8519373!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd3fb3fc0493bf%3A0xb4f54891ed0ce491!2sMJCK%20FARM!5e0!3m2!1sen!2sus!4v1632958945280!5m2!1sen!2sus" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
            <div class="stats-box">
                <h4>Number of Livestock</h4>
                <p><?php echo $numberOfLivestock; ?></p>
                <span>Animals</span>
            </div>
            <div class="stats-box">
                <h4>Number of Admins</h4>
                <p><?php echo $numberOfAdmins; ?></p>
                <span>Admins</span>
            </div>
            <div class="stats-box">
                <h4>Tasks to Do</h4>
                <p><?php echo $numberOfTasks; ?></p>
                <span>Pending Tasks</span>
            </div>
        </div>
    </div>
</body>
</html>