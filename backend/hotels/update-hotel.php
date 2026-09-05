<?php
header('Content-Type: application/json');
require_once '../config/database.php';
$data=json_decode(file_get_contents('php://input'),true);
$id=(int)($data['id']??0); $stmt=$conn->prepare('UPDATE hotels SET name=?,location=?,description=?,image=?,rating=? WHERE id=?');
$stmt->bind_param('ssssdi',$data['name'],$data['location'],$data['description'],$data['image'],$data['rating'],$id);
echo json_encode(['success'=>$stmt->execute()]);
?>