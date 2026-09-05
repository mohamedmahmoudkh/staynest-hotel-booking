<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$phone = trim($data['phone'] ?? '');

if (!$name || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Name, email and password are required']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (name,email,password,phone,role) VALUES (?,?,?,?,?)');
$role = 'user';
$stmt->bind_param('sssss', $name, $email, $hash, $phone, $role);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Registration successful']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Registration failed']);
}
?>