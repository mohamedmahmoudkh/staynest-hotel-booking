<?php
header('Content-Type: application/json');
require_once '../middleware/auth.php';
requireAuth();
require_once '../config/database.php';

$stmt = $conn->prepare('SELECT id,name,email,phone,role,created_at FROM users WHERE id=?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
echo json_encode($stmt->get_result()->fetch_assoc());
?>