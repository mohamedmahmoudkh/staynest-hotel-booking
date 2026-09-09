<?php
// Extra file, not in the original list, but required for the
// "change booking status between confirmed/cancelled" admin action.
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();

$data = json_decode(file_get_contents("php://input"), true);
$booking_id = isset($data['booking_id']) ? intval($data['booking_id']) : 0;
$status = isset($data['status']) ? trim($data['status']) : '';

$allowed_statuses = ['confirmed', 'cancelled'];

if ($booking_id <= 0 || !in_array($status, $allowed_statuses, true)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "A valid booking_id and status are required"]);
    exit;
}

$stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $booking_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Booking status updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "No changes made, or booking not found"]);
}

$stmt->close();
$conn->close();
