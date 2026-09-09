<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();
$data = json_decode(file_get_contents("php://input"), true) ?: [];

$id = (int)($data["id"] ?? 0);
$name = trim($data["name"] ?? "");
$location = trim($data["location"] ?? "");
$description = trim($data["description"] ?? "");
$image = trim($data["image"] ?? "");
$rating = (float)($data["rating"] ?? 0);

if ($id <= 0 || $name === "" || $location === "" || $rating < 0 || $rating > 5) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Valid hotel data is required"]);
    exit;
}

$stmt = $conn->prepare("UPDATE hotels SET name = ?, location = ?, description = ?, image = ?, rating = ? WHERE id = ?");
$stmt->bind_param("ssssdi", $name, $location, $description, $image, $rating, $id);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not update hotel"]);
    exit;
}
if ($stmt->affected_rows === 0) {
    $check = $conn->prepare("SELECT id FROM hotels WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Hotel not found"]);
        exit;
    }
    $check->close();
}

echo json_encode(["success" => true, "message" => "Hotel updated successfully"]);
$stmt->close();
$conn->close();
