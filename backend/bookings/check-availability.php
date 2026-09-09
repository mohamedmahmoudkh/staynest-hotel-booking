<?php
header("Content-Type: application/json");
require_once "../config/database.php";

$roomId = (int)($_GET["room_id"] ?? 0);
$checkIn = trim($_GET["check_in"] ?? "");
$checkOut = trim($_GET["check_out"] ?? "");

$inDate = DateTime::createFromFormat("Y-m-d", $checkIn);
$outDate = DateTime::createFromFormat("Y-m-d", $checkOut);

if ($roomId <= 0 || !$inDate || !$outDate || $inDate >= $outDate) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Valid room ID and dates are required"]);
    exit;
}

$stmt = $conn->prepare("SELECT available FROM rooms WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $roomId);
$stmt->execute();
$roomResult = $stmt->get_result();

if ($roomResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Room not found"]);
    exit;
}

$room = $roomResult->fetch_assoc();
$stmt->close();

if ((int)$room["available"] !== 1) {
    echo json_encode(["success" => true, "message" => "Room is unavailable", "data" => ["available" => false]]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT id FROM bookings
     WHERE room_id = ? AND status = 'confirmed'
       AND check_in < ? AND check_out > ?
     LIMIT 1"
);
$stmt->bind_param("iss", $roomId, $checkOut, $checkIn);
$stmt->execute();

$available = $stmt->get_result()->num_rows === 0;

echo json_encode([
    "success" => true,
    "message" => $available ? "Room is available" : "Room is not available",
    "data" => ["available" => $available]
]);

$stmt->close();
$conn->close();
