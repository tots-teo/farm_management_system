<?php
    include "db.php";

    if(isset($_GET['id'])){
        $id = $_get['id'];
        $sql = "DELETE FROM 'farm_management' where id=$id";
        $conn->query($sql);
    }
    header('location:/tasks/index.php');
    exit;
?>