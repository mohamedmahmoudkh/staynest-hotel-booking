<?php
include("../config/database.php");
header('Content-Type: application/json');

$query = "SELECT * FROM hotels";
$result = mysqli_query($conn, $query);

$hotels = [];
if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $hotels[] = $row;
  }
  echo json_encode(["status" => "success", "data" => $hotels]);
} else {
  echo json_encode(["status" => "error", "message" => "No hotels found"]);
}
