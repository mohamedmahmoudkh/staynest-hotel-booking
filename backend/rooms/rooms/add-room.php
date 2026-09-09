<?php
include("../config/database.php");
header('Content-Type: application/json');

$hotel_id = $_POST['hotel_id'] ?? '';
$room_type = $_POST['room_type'] ?? '';
$price = $_POST['price'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$description = $_POST['description'] ?? '';
$image = $_POST['image'] ?? '';
$available = $_POST['available'] ?? '';

$query = "INSERT INTO rooms (hotel_id, room_type, price, capacity, description, image, available) 
          VALUES ('$hotel_id', '$room_type', '$price', '$capacity', '$description', '$image', '$available')";

if (mysqli_query($conn, $query)) {
    echo json_encode(["status" => "success", "message" => "Room added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}
