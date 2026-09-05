<?php
header('Content-Type: application/json');
require_once '../middleware/auth.php';
requireAuth();
require_once '../config/database.php';
$stmt=$conn->prepare('SELECT b.*,r.room_type,h.name AS hotel_name FROM bookings b JOIN rooms r ON b.room_id=r.id JOIN hotels h ON r.hotel_id=h.id WHERE b.user_id=? ORDER BY b.created_at DESC');
$stmt->bind_param('i',$_SESSION['user_id']); $stmt->execute(); echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
?>