<?php
header('Content-Type: application/json');
require_once '../middleware/auth.php';
requireAuth();
require_once '../config/database.php';

$data=json_decode(file_get_contents('php://input'),true);
$roomId=(int)($data['room_id']??0); $checkIn=$data['check_in']??''; $checkOut=$data['check_out']??''; $guests=(int)($data['guests']??1);

$stmt=$conn->prepare('SELECT price FROM rooms WHERE id=? AND available=1');
$stmt->bind_param('i',$roomId); $stmt->execute(); $room=$stmt->get_result()->fetch_assoc();
if(!$room){http_response_code(400); echo json_encode(['error'=>'Room unavailable']); exit;}

$days=max(1,(strtotime($checkOut)-strtotime($checkIn))/86400);
$total=$days*(float)$room['price'];
$status='confirmed';
$stmt=$conn->prepare('INSERT INTO bookings(user_id,room_id,check_in,check_out,guests,total_price,status) VALUES(?,?,?,?,?,?,?)');
$stmt->bind_param('iissids',$_SESSION['user_id'],$roomId,$checkIn,$checkOut,$guests,$total,$status);
echo json_encode(['success'=>$stmt->execute(),'total_price'=>$total]);
?>