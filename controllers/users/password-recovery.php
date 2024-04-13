<?php

User::isGuest();

$inputData = getJSONData();

$validationResult = UserValidation::validatePasswordRecovery($inputData);

if ($validationResult["success"] !== true) {
    Response::badRequest($validationResult["errors"])->send();
}

$resetPasswordToken = User::passwordRecovery($inputData->email, $inputData->reset_token, $inputData->password);

Session::deleteItem(RESET_TOKEN_SENDED_KEY);

Response::created()->send();