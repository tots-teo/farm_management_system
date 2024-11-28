<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login page/login.php");
    exit;
}

// Check user role for access control
$role = $_SESSION['role'];

// Include the dashboard functions file for statistics
include '../dashboard/dashboardfunction.php';
include '../db.php'; // Include the database connection

// Create an instance of DashboardStats
$dashboardStats = new DashboardStats($conn);
$stats = $dashboardStats->updateStats();

// Fetch statistics
$numberOfAdmins = 1;
$numberOfTasks = 2; 
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
        <a href="../dashboard/dashboard.php">
            <img src="../assets/dashboard.png" alt="Livestock">Dashboard
        </a>
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
                <iframe src="https://shorturl.at/WMx3v" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
            <div class="stats-box">
                <h4>Number of Livestock</h4>
                <p><?php echo $dashboardStats->updateStats(); ?></p>
            </div>
            <div class="stats-box">
                <h4>Number of Admins</h4>
                <p><?php echo $numberOfAdmins; ?></p>
            </div>
            <div class="stats-box">
                <h4>Number of Tasks</h4>
                <p><?php echo $numberOfTasks; ?></p>
            </div>
        </div>
    </div>
</body>
</html>