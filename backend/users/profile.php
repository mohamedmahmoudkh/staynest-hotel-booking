<?php

header("Content-Type: application/json");

require_once "../config/database.php";
require_once "../middleware/auth.php";


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require_login();

$user_id = $_SESSION["user_id"];


/*
|--------------------------------------------------------------------------
| Get Current User
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT
        id,
        name,
        email,
        phone,
        role,
        created_at
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| User Not Found
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
| Response
|--------------------------------------------------------------------------
*/

echo json_encode([
    "success" => true,
    "message" => "Profile fetched successfully",
    "user" => $user
]);


$stmt->close();
$conn->close();