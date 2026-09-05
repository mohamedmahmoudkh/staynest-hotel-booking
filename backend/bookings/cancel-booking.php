<?php
header('Content-Type: application/json');
require_once '../middleware/auth.php';
requireAuth();
require_once '../config/database.php';
$data=json_decode(file_get_contents('php://input'),true); $id=(int)($data['id']??0); $status='cancelled';
$stmt=$conn->prepare('UPDATE bookings SET status=? WHERE id=? AND user_id=?'); $stmt->bind_param('sii',$status,$id,$_SESSION['user_id']); echo json_encode(['success'=>$stmt->execute()]);
?>