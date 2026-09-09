<?php
include("../config/database.php");
header('Content-Type: application/json');

$hotel_id = $_GET['id'] ?? '';
$query = "SELECT * FROM rooms WHERE hotel_id = '$hotel_id' AND available = 1";
$result = mysqli_query($conn, $query);

$rooms = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($room = mysqli_fetch_assoc($result)) {
        $rooms[] = $room;
    }
    echo json_encode(["status" => "success", "data" => $rooms]);
} else {
    echo json_encode(["status" => "error", "message" => "No available rooms found"]);
}
