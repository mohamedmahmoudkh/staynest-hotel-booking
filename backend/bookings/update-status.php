<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();
$data = json_decode(file_get_contents("php://input"), true) ?: [];

$bookingId = (int)($data["booking_id"] ?? 0);
$status = trim($data["status"] ?? "");
$allowed = ["confirmed", "cancelled", "completed"];

if ($bookingId <= 0 || !in_array($status, $allowed, true)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Valid booking_id and status are required"]);
    exit;
}

$stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $bookingId);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    $check = $conn->prepare("SELECT id FROM bookings WHERE id = ?");
    $check->bind_param("i", $bookingId);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Booking not found"]);
        exit;
    }
    $check->close();
}

echo json_encode(["success" => true, "message" => "Booking status updated successfully"]);
$stmt->close();
$conn->close();
