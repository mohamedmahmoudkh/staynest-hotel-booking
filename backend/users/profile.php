<?php

header("Content-Type: application/json");

require_once "../middleware/auth.php";
require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| Allow GET requests only
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Method not allowed"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Require Authentication
|--------------------------------------------------------------------------
*/

require_login();


/*
|--------------------------------------------------------------------------
| Get Logged-in User ID
|--------------------------------------------------------------------------
*/

$userId = $_SESSION["user_id"];


/*
|--------------------------------------------------------------------------
| Get User From Database
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT id, name, email, phone, role, created_at
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $userId);

$stmt->execute();

$result = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| Check User Exists
|--------------------------------------------------------------------------
*/

if ($result->num_rows === 0) {

    $stmt->close();
    $conn->close();

    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);

    exit;
}


$user = $result->fetch_assoc();


/*
|--------------------------------------------------------------------------
| Return User Profile
|--------------------------------------------------------------------------
*/

http_response_code(200);

echo json_encode([
    "success" => true,
    "user" => $user
]);


$stmt->close();
$conn->close();
