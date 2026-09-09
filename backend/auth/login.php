<?php

header("Content-Type: application/json");

require_once "../config/database.php";
require_once "../middleware/auth.php";


/*
|--------------------------------------------------------------------------
| Start Session
|--------------------------------------------------------------------------
*/

start_session_safe();


/*
|--------------------------------------------------------------------------
| Read Request Data
|--------------------------------------------------------------------------
*/

$data = $_POST;

$contentType = $_SERVER["CONTENT_TYPE"] ?? "";

if (stripos($contentType, "application/json") !== false) {

    $json = file_get_contents("php://input");

    $jsonData = json_decode($json, true);

    if (is_array($jsonData)) {
        $data = $jsonData;
    }
}


/*
|--------------------------------------------------------------------------
| Get Fields
|--------------------------------------------------------------------------
*/

$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if ($email === "" || $password === "") {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Email and password are required"
    ]);

    exit;
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid email address"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Find User
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT
        id,
        name,
        email,
        password,
        phone,
        role
     FROM users
     WHERE email = ?
     LIMIT 1"
);

$stmt->bind_param("s", $email);

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

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Invalid email or password"
    ]);

    exit;
}


$user = $result->fetch_assoc();


/*
|--------------------------------------------------------------------------
| Verify Password
|--------------------------------------------------------------------------
*/

if (!password_verify($password, $user["password"])) {

    $stmt->close();
    $conn->close();

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Invalid email or password"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Regenerate Session ID
|--------------------------------------------------------------------------
*/

session_regenerate_id(true);


/*
|--------------------------------------------------------------------------
| Store User Session
|--------------------------------------------------------------------------
*/

$_SESSION["user_id"] = $user["id"];
$_SESSION["role"] = $user["role"];


/*
|--------------------------------------------------------------------------
| Response
|--------------------------------------------------------------------------
*/

echo json_encode([
    "success" => true,
    "message" => "Login successful",
    "user" => [
        "id" => $user["id"],
        "name" => $user["name"],
        "email" => $user["email"],
        "phone" => $user["phone"],
        "role" => $user["role"]
    ]
]);


$stmt->close();
$conn->close();