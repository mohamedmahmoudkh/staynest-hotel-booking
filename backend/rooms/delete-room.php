<?php
include("../config/database.php");
header('Content-Type: application/json');

$id = $_POST['id'] ?? '';

$result = mysqli_query($conn, "SELECT * FROM rooms WHERE id = '$id'");

if ($result && mysqli_num_rows($result) > 0) {
    mysqli_query($conn, "DELETE FROM rooms WHERE id = '$id'");
    echo json_encode(["status" => "success", "message" => "Deleted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Room not found"]);
}
