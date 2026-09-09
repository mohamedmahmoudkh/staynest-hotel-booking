<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();

$queries = [
    "total_users" => "SELECT COUNT(*) AS total FROM users",
    "total_hotels" => "SELECT COUNT(*) AS total FROM hotels",
    "total_rooms" => "SELECT COUNT(*) AS total FROM rooms",
    "total_bookings" => "SELECT COUNT(*) AS total FROM bookings"
];

$data = [];
foreach ($queries as $key => $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Could not load dashboard statistics"]);
        exit;
    }
    $data[$key] = (int)$result->fetch_assoc()["total"];
}

echo json_encode([
    "success" => true,
    "message" => "Stats fetched successfully",
    "data" => $data
]);

$conn->close();
