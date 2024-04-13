<?php

User::isGuest();

User::checkLoginAttempts();

$inputData = getJSONData();

$validationResult = UserValidation::validateLogin($inputData);

if ($validationResult["success"] !== true) {
    Response::badRequest($validationResult["errors"])->send();
}

$token = User::login($inputData);

if (!$token) {
    Response::badRequest("invalid_credentials")->send();
}

$item = User::getItem("email", $inputData->email, "username, options, email, confirmation_token");

Session::deleteItem(MAX_LOGIN_ATTEMPTS_KEY);

Response::created(["user" => $item, "token" => $token])->send();
