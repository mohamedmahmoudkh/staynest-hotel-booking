<?php
header('Content-Type: application/json');
require_once '../config/database.php';
$id=(int)($_GET['id']??0); $stmt=$conn->prepare('DELETE FROM hotels WHERE id=?'); $stmt->bind_param('i',$id); echo json_encode(['success'=>$stmt->execute()]);
?>