<?php
// Database connection details
$host = 'localhost';
$port = '3307';          // MySQL port
$db   = 'user_review';   // Database name
$user = 'root';          // MySQL username (default for Ampps)
$pass = 'mysql';         // MySQL password

// Read JSON body
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];       // Retrieve the review ID
$review = $data['review']; // The new review content

// Create the connection using the correct parameters
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the update query to change the review
$stmt = $conn->prepare("UPDATE reviews SET review = ? WHERE id = ?");
$stmt->bind_param("si", $review, $id);  // "si" means string and integer parameters

// Execute the query and check if the update was successful
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Review updated successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Error updating review."]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
