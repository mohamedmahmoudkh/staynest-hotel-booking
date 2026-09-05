<?php
header('Content-Type: application/json');
require_once '../config/database.php';
$data=json_decode(file_get_contents('php://input'),true);
$stmt=$conn->prepare('INSERT INTO hotels(name,location,description,image,rating) VALUES(?,?,?,?,?)');
$name=$data['name']??'';$location=$data['location']??'';$description=$data['description']??'';$image=$data['image']??'';$rating=(float)($data['rating']??0);
$stmt->bind_param('ssssd',$name,$location,$description,$image,$rating);
echo json_encode(['success'=>$stmt->execute()]);
?>