<?php
$host = 'localhost';
$port = '3307';          // MySQL port
$db   = 'forum_db';      
$user = 'root';          
$pass = 'mysql';          

try {
    // Create PDO connection with correct port
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, show error as JSON
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>


