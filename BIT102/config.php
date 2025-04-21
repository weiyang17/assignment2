<?php
header('Content-Type:text/html;charset=utf-8');

// Database connection details
$host = 'localhost';  // Replace with your MySQL server address, typically 'localhost'
$port = '3307';       // Use the correct MySQL port (3307 in your case)
$username = 'root';   // MySQL username (default for Ampps)
$password = 'mysql';  // MySQL password (replace if necessary)
$database = 'zuoye_book'; // Name of the database

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to the database!";
?>
