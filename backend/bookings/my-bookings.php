<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_login();
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT
        b.id AS booking_id,
        h.name AS hotel_name,
        r.room_number,
        r.room_type,
        b.check_in,
        b.check_out,
        b.guests,
        b.total_price,
        b.status
     FROM bookings b
     JOIN rooms r ON b.room_id = r.id
     JOIN hotels h ON r.hotel_id = h.id
     WHERE b.user_id = ?
     ORDER BY b.created_at DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode([
    "success" => true,
    "message" => "Bookings fetched successfully",
    "data" => $bookings
]);

$stmt->close();
$conn->close();
