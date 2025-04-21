<?php
session_start();

// Database connection details
$host = 'localhost';
$port = '3307'; // Use the correct port for your MySQL (3307 in this case)
$dbname = 'worldtraveller'; // Database name
$username = 'root'; // MySQL username (default for Ampps)
$password = 'mysql'; // MySQL password (if different from root)

try {
    // Create a PDO connection using the above details
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
    $pdo->exec("SET NAMES 'utf8mb4'"); // Ensure utf8mb4 character set for better compatibility
} catch(PDOException $e) {
    // If connection fails, output an error message
    die("Database connection failed: " . $e->getMessage());
}
?>