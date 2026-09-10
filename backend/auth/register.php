<?php

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
| Get User Data
|--------------------------------------------------------------------------
*/

$name = trim($data["name"] ?? "");
$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";
$phone = trim($data["phone"] ?? "");


/*
|--------------------------------------------------------------------------
| Validate Required Fields
|--------------------------------------------------------------------------
*/

if (
    $name === "" ||
    $email === "" ||
    $password === "" ||
    $phone === ""
) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "All fields are required"
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
| Validate Password
|--------------------------------------------------------------------------
*/

if (strlen($password) < 6) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Password must be at least 6 characters"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Check if Email Already Exists
|--------------------------------------------------------------------------
*/

$checkEmail = $conn->prepare(
    "SELECT id
     FROM users
     WHERE email = ?
     LIMIT 1"
);

$checkEmail->bind_param("s", $email);

$checkEmail->execute();

$result = $checkEmail->get_result();

if ($result->num_rows > 0) {

    $checkEmail->close();

    http_response_code(409);

    echo json_encode([
        "success" => false,
        "message" => "Email already exists"
    ]);

    exit;
}

$checkEmail->close();


/*
|--------------------------------------------------------------------------
| Hash Password
|--------------------------------------------------------------------------
*/

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/*
|--------------------------------------------------------------------------
| Insert User
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "INSERT INTO users
        (name, email, password, phone)
     VALUES
        (?, ?, ?, ?)"
);

$stmt->bind_param(
    "ssss",
    $name,
    $email,
    $hashedPassword,
    $phone
);


/*
|--------------------------------------------------------------------------
| Execute Insert
|--------------------------------------------------------------------------
*/

if ($stmt->execute()) {

    $userId = $stmt->insert_id;

    http_response_code(201);

    echo json_encode([
        "success" => true,
        "message" => "Registration successful",
        "user_id" => $userId
    ]);
} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Registration failed"
    ]);
}


$stmt->close();
$conn->close();
