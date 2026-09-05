<?php
header('Content-Type: application/json');
require_once '../config/database.php';
$hotelId=(int)($_GET['hotel_id']??0); $stmt=$conn->prepare('SELECT * FROM rooms WHERE hotel_id=?'); $stmt->bind_param('i',$hotelId); $stmt->execute(); echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
?>