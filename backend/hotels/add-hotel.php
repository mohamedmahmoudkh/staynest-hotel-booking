<?php
header("Content-Type: application/json");
require_once "../config/database.php";
require_once "../middleware/auth.php";

require_admin();
$data = json_decode(file_get_contents("php://input"), true) ?: [];

$name = trim($data["name"] ?? "");
$location = trim($data["location"] ?? "");
$description = trim($data["description"] ?? "");
$image = trim($data["image"] ?? "");
$rating = (float)($data["rating"] ?? 0);

if ($name === "" || $location === "" || $rating < 0 || $rating > 5) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Name, location and a rating between 0 and 5 are required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO hotels (name, location, description, image, rating) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssd", $name, $location, $description, $image, $rating);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not add hotel"]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Hotel added successfully",
    "data" => ["id" => $stmt->insert_id]
]);

$stmt->close();
$conn->close();
