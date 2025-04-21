<?php
// Database connection details
$host = 'localhost';
$port = '3307';          // MySQL port
$db   = 'user_review';   // Database name
$user = 'root';          // MySQL username (default for Ampps)
$pass = 'mysql';         // MySQL password

// Get ID from query parameter
$id = $_GET['id'];  // The review ID to delete

// Create connection using the correct parameters
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete query
$stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
$stmt->bind_param("i", $id);  // "i" means integer for ID

// Execute the query and check if deletion was successful
if ($stmt->execute()) {
    echo "Review deleted successfully!";
} else {
    echo "Error deleting review.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
