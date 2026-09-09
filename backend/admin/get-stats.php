<?php
// Extra file, not in the original list, but required so
// frontend/admin/dashboard.html has an API to read totals from.
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();

$hotels = $conn->query("SELECT COUNT(*) AS total FROM hotels")->fetch_assoc()['total'];
$rooms = $conn->query("SELECT COUNT(*) AS total FROM rooms")->fetch_assoc()['total'];
$bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

echo json_encode([
    "success" => true,
    "message" => "Stats fetched successfully",
    "data" => [
        "total_hotels" => (int)$hotels,
        "total_rooms" => (int)$rooms,
        "total_bookings" => (int)$bookings,
        "total_users" => (int)$users
    ]
]);

$conn->close();
