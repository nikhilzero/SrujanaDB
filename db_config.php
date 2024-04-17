<?php
// Database connection configuration
$dbHost = 'elvis.rowan.edu';
$dbUsername = 'Chiluk58';
$dbPassword = '1Pink3car!';
$dbName = 'Chiluk58';

// Create a new MySQLi connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
