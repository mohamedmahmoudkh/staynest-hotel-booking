<?php
header('Content-Type: application/json');
require_once '../config/database.php';
$data=json_decode(file_get_contents('php://input'),true);
$stmt=$conn->prepare('INSERT INTO rooms(hotel_id,room_type,price,capacity,description,image,available) VALUES(?,?,?,?,?,?,?)');
$stmt->bind_param('isdissi',$data['hotel_id'],$data['room_type'],$data['price'],$data['capacity'],$data['description'],$data['image'],$data['available']);
?>
