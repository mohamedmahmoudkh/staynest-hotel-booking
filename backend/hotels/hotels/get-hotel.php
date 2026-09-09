
<?php
include("../config/database.php");
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $hotel_id = $_GET['id'];
    $query = "SELECT * FROM hotels WHERE id = '$hotel_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $hotel = mysqli_fetch_assoc($result);
        echo json_encode(["status" => "success", "data" => $hotel]);
    } else {
        echo json_encode(["status" => "error", "message" => "Hotel not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>