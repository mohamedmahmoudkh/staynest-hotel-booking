<?php
include("../config/database.php");
header('Content-Type: application/json');

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$location = $_POST['location'] ?? '';
$description = $_POST['description'] ?? '';
$rating = $_POST['rating'] ?? '';

$result = mysqli_query($conn, "SELECT * FROM hotels WHERE id = '$id'");

if ($result && mysqli_num_rows($result) > 0) {
    $query = "UPDATE hotels SET name = '$name', location = '$location', description = '$description', rating = '$rating' WHERE id = '$id'";
    $run = mysqli_query($conn, $query);

    if ($run) {
        echo json_encode(["status" => "success", "message" => "Updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Hotel not found"]);
}
