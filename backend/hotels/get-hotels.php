<?php
header("Content-Type: application/json");
require_once "../config/database.php";

$destination = trim($_GET["destination"] ?? "");
$checkIn = trim($_GET["check_in"] ?? "");
$checkOut = trim($_GET["check_out"] ?? "");
$guestsRaw = trim($_GET["guests"] ?? "");
$guests = $guestsRaw !== "" ? (int)$guestsRaw : 0;

$sql = "SELECT h.id, h.name, h.location, h.description, h.image, h.rating, h.created_at
        FROM hotels h
        WHERE 1=1";
$params = [];
$types = "";

if ($destination !== "") {
    $sql .= " AND (h.name LIKE ? OR h.location LIKE ?)";
    $like = "%" . $destination . "%";
    $params[] = $like;
    $params[] = $like;
    $types .= "ss";
}

$hasDateFilter = $checkIn !== "" || $checkOut !== "";
if ($hasDateFilter) {
    $inDate = DateTime::createFromFormat("Y-m-d", $checkIn);
    $outDate = DateTime::createFromFormat("Y-m-d", $checkOut);
    if (!$inDate || !$outDate || $inDate >= $outDate) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Valid check-in and check-out dates are required"]);
        exit;
    }
    $sql .= " AND EXISTS (
        SELECT 1 FROM rooms r
        WHERE r.hotel_id = h.id AND r.available = 1
          " . ($guests > 0 ? "AND r.capacity >= ? " : "") . "
          AND NOT EXISTS (
              SELECT 1 FROM bookings b
              WHERE b.room_id = r.id AND b.status = 'confirmed'
                AND b.check_in < ? AND b.check_out > ?
          )
    )";
    if ($guests > 0) {
        $params[] = $guests;
        $types .= "i";
    }
    $params[] = $checkOut;
    $params[] = $checkIn;
    $types .= "ss";
} elseif ($guests > 0) {
    $sql .= " AND EXISTS (
        SELECT 1 FROM rooms r
        WHERE r.hotel_id = h.id AND r.available = 1 AND r.capacity >= ?
    )";
    $params[] = $guests;
    $types .= "i";
}

$sql .= " ORDER BY h.id DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not prepare hotel search"]);
    exit;
}

if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();

echo json_encode([
    "success" => true,
    "message" => "Hotels fetched successfully",
    "data" => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)
]);

$stmt->close();
$conn->close();
