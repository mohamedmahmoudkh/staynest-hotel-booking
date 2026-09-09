<?php
include("../config/database.php");
header('Content-Type: application/json');

$name = $_POST['name'] ?? '';
$location = $_POST['location'] ?? '';
$description = $_POST['description'] ?? '';
$image = $_POST['image'] ?? '';
$rating = $_POST['rating'] ?? '';

$query = "INSERT INTO hotels (name, location, description, image, rating) 
          VALUES ('$name', '$location', '$description', '$image', '$rating')";

if (mysqli_query($conn, $query)) {
    echo json_encode(["status" => "success", "message" => "Hotel added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}
