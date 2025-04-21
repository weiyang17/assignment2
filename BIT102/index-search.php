<?php
// Database connection details
$host = 'localhost';
$port = '3307';          // MySQL port
$db   = 'search_history';   // Database name
$user = 'root';          // MySQL username (default for Ampps)
$pass = 'mysql';         // MySQL password

// Create connection using the correct parameters
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // For production, log error to file
}

header('Content-Type: application/json');
session_start();

// Fetch user's session ID or handle login validation
$userId = $_SESSION['id'] ?? null;
$action = $_GET['action'] ?? '';  // 'get', 'search', or 'delete'
$input = json_decode(file_get_contents('php://input'), true);

switch ($action) {
    case "get":
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Please log in']);
            exit;
        }

        // Fetch search history for the logged-in user
        $stmt = $conn->prepare("SELECT * FROM search_history WHERE user_id = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error)); // Error handling
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Return the search history as JSON
        if (empty($history)) {
            echo json_encode(['success' => true, 'message' => 'ok', "data" => []]);
        } else {
            echo json_encode(['success' => true, 'message' => 'ok', "data" => $history]);
        }
        break;

    case "search":
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Please log in']);
            exit;
        }

        // Define valid search terms and associated URLs
        $arr = [
            "Home" => "index.html",
            "Forum" => "Forum/forum.html",
            "Booking" => "index_booking.html",
            "Paris" => "paris.html",
            "London" => "London.html",
            "Rome" => "Rome.html",
            "Beijing" => "Beijing.html",
            "Tokyo" => "Tokyo.html",
            "KualaLumpur" => "KualaLumpur.html",
        ];

        // Check if 'search' parameter exists and is valid
        $search = $_GET['search'] ?? '';
        if (empty($search)) {
            echo json_encode(['success' => false, 'message' => 'Search term is empty']);
            exit;
        }

        // Sanitize the search term to prevent XSS
        $search = htmlspecialchars(trim($search)); 

        // Validate if search term exists in the predefined list
        $url = $arr[$search] ?? "";

        if (!$url) {
            echo json_encode(['success' => false, 'message' => 'Search term not found']);
            exit;
        }

        // Insert the search history into the database
        $stmt = $conn->prepare("INSERT INTO search_history (user_id, content, url) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $search, $url);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'ok', 'data' => [
            "content" => $search,
            "url" => $url,
        ]]);
        break;

    case "delete":
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Please log in', 'code' => 401]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM search_history WHERE id = ?");
        $stmt->bind_param("i", $input['id']);
        $stmt->execute();

        if ($conn->affected_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Failed to delete search history']);
            exit;
        }

        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>
