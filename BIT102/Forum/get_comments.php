<?php
require 'db.php';

$stmt = $pdo->query("SELECT * FROM comments ORDER BY id DESC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($comments);
?>
