<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_login();

$data = json_decode(file_get_contents("php://input"), true);

$room_id = isset($data['room_id']) ? intval($data['room_id']) : 0;
$check_in = isset($data['check_in']) ? trim($data['check_in']) : '';
$check_out = isset($data['check_out']) ? trim($data['check_out']) : '';
$guests = isset($data['guests']) ? intval($data['guests']) : 0;
$user_id = $_SESSION['user_id'];

if ($room_id <= 0 || $check_in === '' || $check_out === '' || $guests <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "room_id, check_in, check_out and guests are required"]);
    exit;
}

$check_in_date = DateTime::createFromFormat('Y-m-d', $check_in);
$check_out_date = DateTime::createFromFormat('Y-m-d', $check_out);

if (!$check_in_date || !$check_out_date || $check_in_date >= $check_out_date) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "check_out must be a valid date after check_in"]);
    exit;
}

// 1. Make sure the room exists
$stmt = $conn->prepare("SELECT price, capacity FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$roomResult = $stmt->get_result();

if ($roomResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Room not found"]);
    exit;
}

$room = $roomResult->fetch_assoc();
$stmt->close();

if ($guests > $room['capacity']) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Number of guests exceeds room capacity"]);
    exit;
}

// 2. Make sure the room is available for these dates
$stmt = $conn->prepare(
    "SELECT id FROM bookings
     WHERE room_id = ? AND status = 'confirmed'
     AND check_in < ? AND check_out > ?"
);
$stmt->bind_param("iss", $room_id, $check_out, $check_in);
$stmt->execute();
$conflictResult = $stmt->get_result();

if ($conflictResult->num_rows > 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Room is not available for the selected dates"]);
    exit;
}
$stmt->close();

// 3. Calculate price
$nights = $check_in_date->diff($check_out_date)->days;
$total_price = $nights * $room['price'];
$status = "confirmed";

// 4. Insert booking
$stmt = $conn->prepare(
    "INSERT INTO bookings (user_id, room_id, check_in, check_out, guests, total_price, status)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("iissids", $user_id, $room_id, $check_in, $check_out, $guests, $total_price, $status);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        "success" => true,
        "message" => "Booking created successfully",
        "booking_id" => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not create booking"]);
}

$stmt->close();
$conn->close();
