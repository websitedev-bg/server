<?php

User::isGuest();

$inputData = getJSONData();

$validationResult = UserValidation::validateRegister($inputData->email, $inputData->password, $inputData->cpassword, $inputData->username);

if ($validationResult["success"] !== true) {
    Response::badRequest($validationResult["errors"])->send();
}

$item = User::getItem("email", $inputData->email);

if ($item) {
    Response::badRequest("this_email_already_exists")->send();
}

$registrationResult = User::register($inputData->email, $inputData->password, $inputData->username, $inputData->options);

if ($registrationResult["success"] !== true) {
    Response::badRequest("unexpected_error")->send();
}

$user = User::getItem("email", $inputData->email, "username, email, options");

$result = User::sendConfirmationEmail($inputData);

if ($result) {
    Response::ok($user)->send();
    Session::deleteItem(CONFIRMATION_TOKEN);
}