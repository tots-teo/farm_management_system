<?php
class Sidebar {
    private $role;

    public function __construct($role) {
        $this->role = $role;
    }

    public function render() {
        echo '<div class="sidebar">';
        echo '    <div class="sidebar-header">';
        echo '        <img src="../Pictures/mjck farm.jpg" alt="MJCK Farm Logo">';
        echo '        <h3>MJCK Farm</h3>';
        echo '    </div>';
        echo '    <a href="../dashboard/dashboard.php">';
        echo '        <img src="../Pictures/dashboard.png" alt="Livestock">Dashboard';
        echo '    </a>';
        echo '    <a href="../livestock management/livestock_management.php">';
        echo '        <img src="../Pictures/livestock.png" alt="Livestock">Livestock Management';
        echo '    </a>';
        echo '    <a href="crop_management.php">';
        echo '        <img src="../Pictures/crop.png" alt="Crops">Crop Management';
        echo '    </a>';
        echo '    <a href="feed_inventory.php">';
        echo '        <img src="../Pictures/feed_inventory.png" alt="Feed Inventory">Feed Inventory';
        echo '    </a>';
        echo '    <a href="../Task Manager/Task_Manager.php">';
        echo '        <img src="../Pictures/task.png" alt="Task Manager">Task Manager';
        echo '    </a>';
        
        if ($this->role === 'Admin') {
            echo '    <a href="../Admin/profile.php">';
            echo '        <img src="../Pictures/farmer.png" alt="Admin Panel">Admin Panel';
            echo '    </a>';
        }

        echo '    <a href="../dashboard/logout.php" class="logout-btn">';
        echo '        <img src="../Pictures/logout.png" alt="Logout">Logout';
        echo '    </a>';
        echo '</div>';
    }
}
?>