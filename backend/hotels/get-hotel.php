<?php
header('Content-Type: application/json');
require_once '../config/database.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM hotels WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
echo json_encode($stmt->get_result()->fetch_assoc() ?: []);
?>