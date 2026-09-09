<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();
$data = json_decode(file_get_contents("php://input"), true) ?: [];
$id = (int)($data["id"] ?? $_GET["id"] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Valid hotel ID is required"]);
    exit;
}

$check = $conn->prepare("SELECT COUNT(*) AS total FROM rooms WHERE hotel_id = ?");
$check->bind_param("i", $id);
$check->execute();
if ((int)$check->get_result()->fetch_assoc()["total"] > 0) {
    http_response_code(409);
    echo json_encode(["success" => false, "message" => "Cannot delete a hotel while it still has rooms. Delete eligible rooms first."]);
    exit;
}
$check->close();

$stmt = $conn->prepare("DELETE FROM hotels WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Hotel not found"]);
    exit;
}

echo json_encode(["success" => true, "message" => "Hotel deleted successfully"]);
$stmt->close();
$conn->close();
