<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$newContent = $data['content'] ?? '';

if (!$id || $newContent === '') {
    echo json_encode(['success' => false, 'error' => 'Missing ID or content']);
    exit;
}

$stmt = $pdo->prepare("UPDATE comments SET content = ? WHERE id = ?");
$success = $stmt->execute([$newContent, $id]);

echo json_encode(['success' => $success]);
?>

