<?php

User::isGuest();

User::checkLoginAttempts();

$inputData = getJSONData();

$validationResult = UserValidation::validateForgotPassword($inputData);

if ($validationResult["success"] !== true) {
    Response::badRequest($validationResult["errors"])->send();
}

$forgotPasswordToken = User::forgotPassword($inputData->email);

$user = User::getItem("email", $inputData->email, "first_name, email");

$result = User::sendForgotPasswordEmail($user);

if ($result) {
    Session::deleteItem(MAX_LOGIN_ATTEMPTS_KEY);
    Session::deleteItem(RESET_TOKEN_SENDED_KEY);
    Session::deleteItem(RESET_TOKEN);
    Response::created()->send();
}