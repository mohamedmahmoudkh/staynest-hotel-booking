<?php
header('Content-Type: application/json');
require_once '../middleware/auth.php';
requireAuth();
if(($_SESSION['role']??'')!=='admin'){http_response_code(403);echo json_encode(['error'=>'Admin only']);exit;}
require_once '../config/database.php';
$result=$conn->query('SELECT b.*,u.name AS user_name,u.email,r.room_type,h.name AS hotel_name FROM bookings b JOIN users u ON b.user_id=u.id JOIN rooms r ON b.room_id=r.id JOIN hotels h ON r.hotel_id=h.id ORDER BY b.created_at DESC');
echo json_encode($result ? $result->fetch_all(MYSQLI_ASSOC) : []);
?>