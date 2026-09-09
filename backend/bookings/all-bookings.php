<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();

$sql = "SELECT b.id AS booking_id, u.id AS user_id, u.name AS user_name, u.email AS user_email,
               h.id AS hotel_id, h.name AS hotel_name, r.id AS room_id, r.room_number, r.room_type,
               b.check_in, b.check_out, b.guests, b.total_price, b.status, b.created_at
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        JOIN hotels h ON r.hotel_id = h.id
        ORDER BY b.created_at DESC";

$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not load bookings: " . $conn->error]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Bookings fetched successfully",
    "data" => $result->fetch_all(MYSQLI_ASSOC)
]);

$conn->close();
