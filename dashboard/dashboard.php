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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        html, body {
            height: 100%;
            overflow: hidden;
            background-color: #f4f4f4;
        }

        body {
            display: flex;
            background-color: #1c1e26;
            color: #cfcfcf;
        }

        .sidebar {
            width: 280px; /* Wider sidebar */
            background-color: #2d2f3b;
            padding: 30px 20px;
            color: #ffffff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar-header img {
            width: 150px;
            height: 150px;
            margin-bottom: 15px;
            border-radius: 50%;
            object-fit: cover;
            object-position : center;
            border: 4px solid #4CAF50;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .sidebar-header img:hover {
            transform: scale(1.05);
        }

        .sidebar-header h3 {
            color: #4CAF50;
            font-size: 1.8em;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 12px;
            color: #9aa0b4;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar a img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background-color: #383b4f;
            color: #4CAF50;
            transform: translateX(10px);
        }

        .logout-btn {
            color: #ff4d4d;
            margin-top: auto;
            margin-bottom: 20px;
        }

        .logout-btn img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            height: 100vh;
            background-color: #f9f9f9;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        header h2 {
            color: #333;
            font-size: 1.8em;
        }

        .dashboard-overview {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: 700px 1fr; /* Make the first row larger for the map overview */
            gap: 20px;
        }

        .stats-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: 1px solid #e0e0e0;
            height: 100px; /* Decrease the height of the stats boxes */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
        }

        .stats-box h4 {
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .stats-box p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 0; /* Remove margin for better alignment */
        }

        .stats-box span {
            font-size: 14px;
            color: #4CAF50;
        }

        .stats-box.map-overview {
            grid-row: 1 / 2; /* Make the map overview span the full height of the first row */
            height: 100%; /* Fill the available space in the first row */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
        }

        .stats-box.map-overview img {
            max-width: 100%; 
            height: auto; 
            border-radius: 8px; 
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="mjck farm.jpg" alt="MJCK Farm Logo">
            <h3>MJCK Farm</h3>
        </div>
        <a href="livestock_management.php">
            <img src="livestock.png" alt="Livestock">Livestock Management
        </a>
        <a href="crop_management.php">
            <img src="crop.png" alt="Crops">Crop Management
        </a>
        <a href="feed_inventory.php">
            <img src="feed_inventory.png" alt="Feed Inventory">Feed Inventory
        </a>
        <a href="task_manager.php">
            <img src="task.png" alt="Task Manager">Task Manager
        </a>
        <?php if ($role === 'Admin'): ?>
            <a href="admin_panel.php">
                <img src="farmer.png" alt="Admin Panel">Admin Panel
            </a>
        <?php endif; ?>
        <a href="logout.php" class="logout-btn">
            <img src="logout.png" alt="Logout">Logout
        </a>
    </div>

    <div class="main-content">
        <header>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></h2>
        </header>

        <div class="dashboard-overview">
            <div class="stats-box map-overview">
                <h4>Map Overview</h4>
                <img src="mjck map.jpg" alt="MJCK Map">
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