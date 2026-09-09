<?php
// NOTE: If auth.php already exists from Task 3/4, just make sure it has
// require_login() and require_admin() with this same behavior — do not
// create a second, conflicting version.

function start_session_safe() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function require_login() {
    start_session_safe();

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        header("Content-Type: application/json");
        echo json_encode([
            "success" => false,
            "message" => "You must be logged in"
        ]);
        exit;
    }
}

function require_admin() {
    require_login();

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        header("Content-Type: application/json");
        echo json_encode([
            "success" => false,
            "message" => "Admin access required"
        ]);
        exit;
    }
}
