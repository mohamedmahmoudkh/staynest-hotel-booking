<?php
header("Content-Type: application/json");

require_once "../middleware/auth.php";
require_once "../config/database.php";

if (!isset($_GET["id"]) || $_GET["id"] == "") {
    echo json_encode([
        "success" => false,
        "message" => "User ID is required"
    ]);
    exit;
}

$id = $_GET["id"];

$stmt = $conn->prepare(
    "SELECT id, name, email, phone, role
     FROM users
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    echo json_encode([
        "success" => true,
        "user" => $user
    ]);
} else {

    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
}
