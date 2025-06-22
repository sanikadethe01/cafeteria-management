<?php
$servername = 'localhost';
$username = 'root';
$password = ''; 
$database = 'cafeteria';

$conn = new mysqli($servername, $username, $password, $database, 3306);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// echo "Connected successfully";
?>
