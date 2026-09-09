<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();

$sql = "SELECT
            b.id AS booking_id,
            u.name AS user_name,
            h.name AS hotel_name,
            r.room_number,
            b.check_in,
            b.check_out,
            b.guests,
            b.total_price,
            b.status
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        JOIN hotels h ON r.hotel_id = h.id
        ORDER BY b.created_at DESC";

$result = $conn->query($sql);

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode([
    "success" => true,
    "message" => "Bookings fetched successfully",
    "data" => $bookings
]);

$conn->close();
