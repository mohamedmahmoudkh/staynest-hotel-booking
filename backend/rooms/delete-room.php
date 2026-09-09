<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();
$d = json_decode(file_get_contents("php://input"), true) ?: [];
$id = (int)($d["id"] ?? $_GET["id"] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Valid room ID is required"]);
    exit;
}

$check = $conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE room_id = ?");
$check->bind_param("i", $id);
$check->execute();
if ((int)$check->get_result()->fetch_assoc()["total"] > 0) {
    http_response_code(409);
    echo json_encode(["success" => false, "message" => "Cannot delete a room that has bookings. Cancel or complete its bookings instead."]);
    exit;
}
$check->close();

$stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Room not found"]);
    exit;
}

echo json_encode(["success" => true, "message" => "Room deleted successfully"]);
$stmt->close();
$conn->close();
