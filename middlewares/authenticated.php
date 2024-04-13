<?php

$token = User::getToken();

if (!User::checkTokenExpiry($token)) {
    Response::badRequest("invalid_token")->send();
}

if (!User::checkTokenExpiry($token)) {
    Response::badRequest("token_expired")->send();
}

if (!User::checkPassword($token)) {
    Response::badRequest("invalid_password")->send();
}
