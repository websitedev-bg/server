<?php

require "middlewares/authenticated.php";

if (!User::hasPermissions(["edit_user_roles"])) {
    Response::unauthorized("access_denied")->send();
}

$inputData = getJSONData();

$validationResult = UserValidation::validateUpdateRoles($inputData);

if ($validationResult["success"] !== true) {
    Response::badRequest($validationResult["errors"])->send();
}

$token = User::getToken();
$decodedData = User::decodeToken($token);
$user = User::getItem("email", $decodedData["email"], "id");

User::updateRoles($user["id"], $inputData->roles);

Response::created()->send();
