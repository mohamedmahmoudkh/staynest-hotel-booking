<?php

session_start();

header("Content-Type: application/json");

require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| Allow POST requests only
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Method not allowed"
    ]);

    exit;
}


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
| Get Login Data
|--------------------------------------------------------------------------
*/

$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";


/*
|--------------------------------------------------------------------------
| Validate Required Fields
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


/*
|--------------------------------------------------------------------------
| Validate Email
|--------------------------------------------------------------------------
*/

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
    "SELECT id, name, email, password, phone, role
     FROM users
     WHERE email = ?
     LIMIT 1"
);

$stmt->bind_param("s", $email);

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
| Store User Information in Session
|--------------------------------------------------------------------------
*/

$_SESSION["user_id"] = $user["id"];
$_SESSION["user_name"] = $user["name"];
$_SESSION["user_email"] = $user["email"];
$_SESSION["user_role"] = $user["role"];


/*
|--------------------------------------------------------------------------
| Remove Password Before Sending Response
|--------------------------------------------------------------------------
*/

unset($user["password"]);


/*
|--------------------------------------------------------------------------
| Successful Login
|--------------------------------------------------------------------------
*/

http_response_code(200);

echo json_encode([
    "success" => true,
    "message" => "Login successful",
    "user" => $user
]);


$stmt->close();
$conn->close();
