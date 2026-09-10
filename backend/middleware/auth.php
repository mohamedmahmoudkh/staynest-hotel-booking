<?php

/*
|--------------------------------------------------------------------------
| Start Session
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| Require Login
|--------------------------------------------------------------------------
*/

function require_login()
{
    if (!isset($_SESSION["user_id"])) {

        http_response_code(401);

        echo json_encode([
            "success" => false,
            "message" => "Authentication required"
        ]);

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Require Admin
|--------------------------------------------------------------------------
*/

function require_admin()
{
    require_login();

    if (
        !isset($_SESSION["user_role"]) ||
        $_SESSION["user_role"] !== "admin"
    ) {

        http_response_code(403);

        echo json_encode([
            "success" => false,
            "message" => "Admin access required"
        ]);

        exit;
    }
}
