<?php
    include "../db.php";

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        
    $stmt = $conn->prepare("DELETE FROM `task` WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" specifies the type (integer)
    $stmt->execute();
    $stmt->close();
}
    header('location:../Task Manager/Task_Manager.php');
    exit;
?>