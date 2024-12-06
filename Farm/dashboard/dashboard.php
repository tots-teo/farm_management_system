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
include '../Dashboard/dashboardfunction.php';
include '../db.php'; // Include the database connection
include '../Sidebar/sidebar.php';

// Create an instance of DashboardStats
$dashboardStats = new DashboardStats($conn);
$stats = $dashboardStats->updateStats();
$numberOfAdmins = $dashboardStats->getNumberOfAdmins(); 
$numberOfTasks = $dashboardStats->getNumberOfTasks(); 
$sidebar = new Sidebar($role);

$sidebar->render();
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
    <div class="main-content">
        <header>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></h2>
        </header>

        <div class="dashboard-overview">
            <div class="stats-box map-overview">
                <h4>Map Overview</h4>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5104.5188435168175!2d121.33422347509209!3d13.851937286550417!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd3fb3fc0493bf%3A0xb4f54891ed0ce491!2sMJCK%20FARM!5e1!3m2!1sen!2sph!4v1732851216993!5m2!1sen!2sph" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
            <div class="stats-box">
                <h4>Number of Livestock</h4>
                <p><?php echo $stats; ?></p>
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