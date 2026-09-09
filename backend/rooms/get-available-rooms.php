<?php
header("Content-Type: application/json");
require_once "../config/database.php";

$hotelId = (int)($_GET["hotel_id"] ?? 0);
$checkIn = trim($_GET["check_in"] ?? "");
$checkOut = trim($_GET["check_out"] ?? "");

if ($hotelId <= 0 || $checkIn === "" || $checkOut === "") {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "hotel_id, check_in and check_out are required"]);
    exit;
}

$inDate = DateTime::createFromFormat("Y-m-d", $checkIn);
$outDate = DateTime::createFromFormat("Y-m-d", $checkOut);
if (!$inDate || !$outDate || $inDate >= $outDate) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid date range"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT r.*
     FROM rooms r
     WHERE r.hotel_id = ? AND r.available = 1
       AND NOT EXISTS (
           SELECT 1 FROM bookings b
           WHERE b.room_id = r.id AND b.status = 'confirmed'
             AND b.check_in < ? AND b.check_out > ?
       )
     ORDER BY r.id DESC"
);
$stmt->bind_param("iss", $hotelId, $checkOut, $checkIn);
$stmt->execute();

echo json_encode([
    "success" => true,
    "message" => "Available rooms fetched successfully",
    "data" => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)
]);

$stmt->close();
$conn->close();
