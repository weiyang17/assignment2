<?php
// Database connection details
$host = 'localhost';
$port = '3307';          // MySQL port
$db   = 'user_review';   // Database name
$user = 'root';          // MySQL username (default for Ampps)
$pass = 'mysql';         // MySQL password

// Create connection using the correct parameters
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all reviews from the database
$sql = "SELECT id, name, country, rating, review, created_at FROM reviews ORDER BY created_at DESC";
$result = $conn->query($sql);

$reviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

// Return reviews as JSON
header('Content-Type: application/json');
echo json_encode($reviews);

// Close the database connection
$conn->close();
?>
