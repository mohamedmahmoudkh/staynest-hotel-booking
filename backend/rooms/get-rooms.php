<?php
header("Content-Type: application/json"); require_once "../config/database.php";
$hotelId=(int)($_GET["hotel_id"]??0); $sql="SELECT * FROM rooms"; if($hotelId>0)$sql.=" WHERE hotel_id=".$hotelId; $sql.=" ORDER BY id DESC"; $result=$conn->query($sql); echo json_encode(["success"=>true,"message"=>"Rooms fetched successfully","data"=>$result?$result->fetch_all(MYSQLI_ASSOC):[]]);
