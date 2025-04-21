<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No data received']);
    exit;
}

$name = $data['name'] ?? '';
$title = $data['title'] ?? '';
$content = $data['content'] ?? '';
$date = $data['date'] ?? '';
$time = $data['time'] ?? '';

$stmt = $pdo->prepare("INSERT INTO comments (name, title, content, date, time) VALUES (?, ?, ?, ?, ?)");
$success = $stmt->execute([$name, $title, $content, $date, $time]);
$insertedId = $pdo->lastInsertId();

echo json_encode(['success' => $success, 'id' => $insertedId]);
?>

