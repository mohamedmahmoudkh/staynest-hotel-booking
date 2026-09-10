<?php

header("Content-Type: application/json");

$host = "localhost";
$db_name = "staynest";
$db_username = "root";
$db_password = "";

$conn = new mysqli(
    $host,
    $db_username,
    $db_password,
    $db_name
);

if ($conn->connect_error) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);

    exit;
}

// Support UTF-8 data
$conn->set_charset("utf8mb4");
