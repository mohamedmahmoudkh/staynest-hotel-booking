<?php
header("Content-Type: application/json");

require_once "../config/database.php";

if (
    !isset($_POST["name"]) ||
    !isset($_POST["email"]) ||
    !isset($_POST["password"]) ||
    !isset($_POST["phone"])
) {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$password = $_POST["password"];
$phone = trim($_POST["phone"]);

if ($name == "" || $email == "" || $password == "" || $phone == "") {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

$checkEmail = $conn->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$checkEmail->bind_param("s", $email);
$checkEmail->execute();

$result = $checkEmail->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Email already exists"
    ]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users (name, email, password, phone)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param(
    "ssss",
    $name,
    $email,
    $hashedPassword,
    $phone
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Registration successful"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Registration failed"
    ]);
}
