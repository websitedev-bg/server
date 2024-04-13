<?php

require "middlewares/authenticated.php";

$requiredColumns = "id, email, username, expiry_time, confirmation_token, reset_token, options, created_at, role_id";

$token = User::getToken();
$decodedData = User::decodeToken($token);
$user = User::getItem("email", $decodedData["email"], $requiredColumns);

if ($user["role_id"]) {
    $role = Role::getItem("id", $user["role_id"]);

    if (!$role) {
        $user["role_id"] = null;
    }

    $user["role"] = $role;
    unset($user["role_id"]);
}

Response::ok($user)->send();