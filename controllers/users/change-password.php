<?php

require "middlewares/authenticated.php";

$inputData = getJSONData();

$validationResult = UserValidation::validateChangePassword($inputData);

if ($validationResult["success"] !== true) {
    Response::badRequest($validationResult["errors"])->send();
}

User::changePassword($inputData->password, $inputData->new_password);

Response::ok()->send();
