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


$currentUserId = (int) $_SESSION["user_id"];
$currentUserRole = $_SESSION["role"] ?? "user";


/*
|--------------------------------------------------------------------------
| Get Requested User ID
|--------------------------------------------------------------------------
*/

$id = isset($_GET["id"])
    ? (int) $_GET["id"]
    : 0;


/*
|--------------------------------------------------------------------------
| Validate ID
|--------------------------------------------------------------------------
*/

if ($id <= 0) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Valid user ID is required"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Authorization
|--------------------------------------------------------------------------
|
| Normal users can only view their own information.
| Admins can view any user.
|
*/

if (
    $currentUserRole !== "admin" &&
    $id !== $currentUserId
) {

    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" => "You are not allowed to view this user"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Get User
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

$stmt->bind_param("i", $id);

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
    "message" => "User fetched successfully",
    "user" => $user
]);


$stmt->close();
$conn->close();