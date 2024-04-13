<?php

User::isAuthenticated();

$token = User::getToken();
$decodedData = User::decodeToken($token);
$user = User::getItem("email", $decodedData["email"], "id, first_name, last_name, email, confirmation_token, reset_token, roles");

$user["roles"] = !empty($user["roles"]) ? json_decode($user["roles"]) : null;

Response::ok($user)->send();
