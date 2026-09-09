<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_login();

$data = json_decode(file_get_contents("php://input"), true);
$booking_id = isset($data['booking_id']) ? intval($data['booking_id']) : 0;
$user_id = $_SESSION['user_id'];

if ($booking_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "booking_id is required"]);
    exit;
}

$stmt = $conn->prepare("SELECT user_id, status FROM bookings WHERE id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Booking not found"]);
    exit;
}

$booking = $result->fetch_assoc();
$stmt->close();

if ($booking['user_id'] != $user_id) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "You can only cancel your own booking"]);
    exit;
}

if ($booking['status'] !== 'confirmed') {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Only confirmed bookings can be cancelled"]);
    exit;
}

$stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();

echo json_encode(["success" => true, "message" => "Booking cancelled successfully"]);

$stmt->close();
$conn->close();
