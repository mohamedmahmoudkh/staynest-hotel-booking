<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();
$d = json_decode(file_get_contents("php://input"), true) ?: [];

$hotel = (int)($d["hotel_id"] ?? 0);
$roomNumber = trim($d["room_number"] ?? "");
$type = trim($d["room_type"] ?? "");
$price = (float)($d["price"] ?? 0);
$capacity = (int)($d["capacity"] ?? 0);
$desc = trim($d["description"] ?? "");
$image = trim($d["image"] ?? "");
$available = isset($d["available"]) ? (int)$d["available"] : 1;

if ($hotel <= 0 || $roomNumber === "" || $type === "" || $price <= 0 || $capacity <= 0 || !in_array($available, [0, 1], true)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Valid hotel, room number, type, price and capacity are required"]);
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

$stmt = $conn->prepare("INSERT INTO rooms (hotel_id, room_number, room_type, price, capacity, description, image, available) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issdissi", $hotel, $roomNumber, $type, $price, $capacity, $desc, $image, $available);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not add room"]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Room added successfully",
    "data" => ["id" => $stmt->insert_id]
]);

$stmt->close();
$conn->close();
