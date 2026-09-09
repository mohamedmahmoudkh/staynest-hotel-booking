<?php
// NOTE: This should match the database.php already created in earlier tasks.
// Only use this version if that file does not exist yet.

$host = "localhost";
$db_name = "staynest";
$db_username = "root";
$db_password = "";

$conn = new mysqli($host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}
