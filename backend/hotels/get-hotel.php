<?php
header("Content-Type: application/json");
require_once "../config/database.php";

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Valid hotel ID is required"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, name, location, description, image, rating, created_at FROM hotels WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Hotel not found"]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Hotel fetched successfully",
    "data" => ["hotel" => $result->fetch_assoc()]
]);

$stmt->close();
$conn->close();
