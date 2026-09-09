<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_login();
$userId = (int)$_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT b.id AS booking_id, r.id AS room_id, r.room_number, r.room_type,
            h.id AS hotel_id, h.name AS hotel_name, h.location, h.image AS hotel_image,
            b.check_in, b.check_out, b.guests, b.total_price, b.status, b.created_at
     FROM bookings b
     JOIN rooms r ON b.room_id = r.id
     JOIN hotels h ON r.hotel_id = h.id
     WHERE b.user_id = ?
     ORDER BY b.created_at DESC"
);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not prepare bookings query: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $userId);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not load your bookings: " . $stmt->error]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Bookings fetched successfully",
    "data" => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)
]);

$stmt->close();
$conn->close();
