<?php
header("Content-Type: application/json");

session_start();

require_once "../config/database.php";

if (
    !isset($_POST["email"]) ||
    !isset($_POST["password"])
) {
    echo json_encode([
        "success" => false,
        "message" => "Email and password are required"
    ]);
    exit;
}

$email = trim($_POST["email"]);
$password = $_POST["password"];

if ($email == "" || $password == "") {
    echo json_encode([
        "success" => false,
        "message" => "Email and password are required"
    ]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, password, role FROM users WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = $user["role"];

        echo json_encode([
            "success" => true,
            "message" => "Login successful"
        ]);
    } else {

        echo json_encode([
            "success" => false,
            "message" => "Wrong password"
        ]);
    }
} else {

    echo json_encode([
        "success" => false,
        "message" => "Email not found"
    ]);
}
