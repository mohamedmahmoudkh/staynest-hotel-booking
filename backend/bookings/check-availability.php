<?php
header("Content-Type: application/json");
require_once "../config/database.php";

$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
$check_in = isset($_GET['check_in']) ? trim($_GET['check_in']) : '';
$check_out = isset($_GET['check_out']) ? trim($_GET['check_out']) : '';

if ($room_id <= 0 || $check_in === '' || $check_out === '') {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "room_id, check_in and check_out are required"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT id FROM bookings
     WHERE room_id = ? AND status = 'confirmed'
     AND check_in < ? AND check_out > ?"
);
$stmt->bind_param("iss", $room_id, $check_out, $check_in);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["available" => false, "message" => "Room is not available"]);
} else {
    echo json_encode(["available" => true, "message" => "Room is available"]);
}

$stmt->close();
$conn->close();
