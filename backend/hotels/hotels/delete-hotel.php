<?php
include("../config/database.php");

$id = $_POST['id'] ?? '';

$result = mysqli_query($conn, "SELECT * FROM hotels WHERE id = '$id'");

if ($result && mysqli_num_rows($result) > 0) {
    $checkRooms = mysqli_query($conn, "SELECT * FROM rooms WHERE hotel_id = '$id'");

    if (mysqli_num_rows($checkRooms) > 0) {
        echo "Cannot delete: this hotel has rooms linked to it";
    } else {
        $delete = mysqli_query($conn, "DELETE FROM hotels WHERE id = '$id'");
        if ($delete) {
            echo "Deleted successfully";
        } else {
            echo "Delete failed: " . mysqli_error($conn);
        }
    }
} else {
    echo "Hotel not found";
}
