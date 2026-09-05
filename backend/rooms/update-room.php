<?php
header('Content-Type: application/json');
require_once '../config/database.php';
$data=json_decode(file_get_contents('php://input'),true);
$id=(int)($data['id']??0); $stmt=$conn->prepare('UPDATE rooms SET room_type=?,price=?,capacity=?,description=?,image=?,available=? WHERE id=?');
$stmt->bind_param('sdissii',$data['room_type'],$data['price'],$data['capacity'],$data['description'],$data['image'],$data['available'],$id);
echo json_encode(['success'=>$stmt->execute()]);
?>