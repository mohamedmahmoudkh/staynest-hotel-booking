<?php
include("../config/database.php");

$id = $_POST['id'] ?? '';
$room_type = $_POST['room_type'] ?? '';
$price = $_POST['price'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$description = $_POST['description'] ?? '';
$available = $_POST['available'] ?? '';

$result = mysqli_query($conn, "SELECT * FROM rooms WHERE id = '$id'");

if ($result && mysqli_num_rows($result) > 0) {
    $query = "UPDATE rooms SET 
                room_type = '$room_type', 
                price = '$price', 
                capacity = '$capacity', 
                description = '$description', 
                available = '$available' 
              WHERE id = '$id'";

    $run = mysqli_query($conn, $query);

    if ($run) {
        echo "Updated successfully";
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
} else {
    echo "Room not found";
}
