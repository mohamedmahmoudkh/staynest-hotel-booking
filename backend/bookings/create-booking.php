<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_login();

$data = json_decode(file_get_contents("php://input"), true) ?: [];
$roomId = (int)($data["room_id"] ?? 0);
$checkIn = trim($data["check_in"] ?? "");
$checkOut = trim($data["check_out"] ?? "");
$guests = (int)($data["guests"] ?? 0);
$userId = (int)$_SESSION["user_id"];

$inDate = DateTime::createFromFormat("Y-m-d", $checkIn);
$outDate = DateTime::createFromFormat("Y-m-d", $checkOut);
$validIn = $inDate && $inDate->format("Y-m-d") === $checkIn;
$validOut = $outDate && $outDate->format("Y-m-d") === $checkOut;

if ($roomId <= 0 || !$validIn || !$validOut || $inDate >= $outDate || $guests <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Valid room, dates and guest count are required"]);
    exit;
}

if ($checkIn < date("Y-m-d")) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Check-in cannot be in the past"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT r.price, r.capacity, r.available, r.room_type, r.room_number,
            h.id AS hotel_id, h.name AS hotel_name, h.location, h.image AS hotel_image
     FROM rooms r
     JOIN hotels h ON h.id = r.hotel_id
     WHERE r.id = ? LIMIT 1"
);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not prepare room lookup: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $roomId);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not load the selected room: " . $stmt->error]);
    exit;
}
$roomResult = $stmt->get_result();

if ($roomResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Room not found"]);
    exit;
}

$room = $roomResult->fetch_assoc();
$stmt->close();

if ((int)$room["available"] !== 1) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Room is currently unavailable"]);
    exit;
}

if ($guests > (int)$room["capacity"]) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Number of guests exceeds room capacity"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT id FROM bookings
     WHERE room_id = ? AND status = 'confirmed'
       AND check_in < ? AND check_out > ?
     LIMIT 1"
);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not prepare availability check: " . $conn->error]);
    exit;
}
$stmt->bind_param("iss", $roomId, $checkOut, $checkIn);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not check room availability: " . $stmt->error]);
    exit;
}

if ($stmt->get_result()->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["success" => false, "message" => "Room is not available for the selected dates"]);
    exit;
}
$stmt->close();

$nights = $inDate->diff($outDate)->days;
$totalPrice = $nights * (float)$room["price"];

$stmt = $conn->prepare(
    "INSERT INTO bookings (user_id, room_id, check_in, check_out, guests, total_price, status)
     VALUES (?, ?, ?, ?, ?, ?, 'confirmed')"
);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not prepare booking insert: " . $conn->error]);
    exit;
}
$stmt->bind_param("iissid", $userId, $roomId, $checkIn, $checkOut, $guests, $totalPrice);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not create booking: " . $stmt->error]);
    exit;
}

$bookingId = $stmt->insert_id;
echo json_encode([
    "success" => true,
    "message" => "Booking created successfully",
    "data" => [
        "booking" => [
            "booking_id" => $bookingId,
            "room_id" => $roomId,
            "hotel_id" => (int)$room["hotel_id"],
            "hotel_name" => $room["hotel_name"],
            "hotel_image" => $room["hotel_image"],
            "room_type" => $room["room_type"],
            "room_number" => $room["room_number"],
            "check_in" => $checkIn,
            "check_out" => $checkOut,
            "guests" => $guests,
            "total_price" => $totalPrice,
            "status" => "confirmed",
            "created_at" => date("Y-m-d H:i:s")
        ]
    ],
    "booking_id" => $bookingId
]);

$stmt->close();
$conn->close();
