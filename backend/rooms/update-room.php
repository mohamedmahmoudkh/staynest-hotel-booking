<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();
$data = json_decode(file_get_contents("php://input"), true) ?: [];

$id = (int)($data["id"] ?? 0);
$hotel = (int)($data["hotel_id"] ?? 0);
$roomNumber = trim($data["room_number"] ?? "");
$type = trim($data["room_type"] ?? "");
$price = (float)($data["price"] ?? 0);
$capacity = (int)($data["capacity"] ?? 0);
$description = trim($data["description"] ?? "");
$image = trim($data["image"] ?? "");
$available = (int)($data["available"] ?? 1);

if ($id <= 0 || $hotel <= 0 || $roomNumber === "" || $type === "" || $price <= 0 || $capacity <= 0 || !in_array($available, [0, 1], true)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Valid room data is required"]);
    exit;
}

$check = $conn->prepare("SELECT id FROM hotels WHERE id = ?");
$check->bind_param("i", $hotel);
$check->execute();
if ($check->get_result()->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Hotel not found"]);
    exit;
}
$check->close();

$stmt = $conn->prepare("UPDATE rooms SET hotel_id = ?, room_number = ?, room_type = ?, price = ?, capacity = ?, description = ?, image = ?, available = ? WHERE id = ?");
$stmt->bind_param("issdissii", $hotel, $roomNumber, $type, $price, $capacity, $description, $image, $available, $id);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not update room"]);
    exit;
}
if ($stmt->affected_rows === 0) {
    $check = $conn->prepare("SELECT id FROM rooms WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Room not found"]);
        exit;
    }
    $check->close();
}

echo json_encode(["success" => true, "message" => "Room updated successfully"]);
$stmt->close();
$conn->close();
