<?php
header('Content-Type: application/json');
require_once '../config/database.php';
$result = $conn->query('SELECT id,name,location,description,image,rating,created_at FROM hotels ORDER BY id DESC');
echo json_encode($result ? $result->fetch_all(MYSQLI_ASSOC) : []);
?>