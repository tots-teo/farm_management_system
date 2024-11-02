<?php
// Database configuration
$dbHost = 'localhost:3307'; // or 'localhost:3307' if needed
$dbUsername = 'root'; // Database username
$dbPassword = ''; // Database password
$dbName = 'smart_farm'; // Replace with your actual database name

// Create connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
