<?php

require "middlewares/authenticated.php";
require "middlewares/is-admin.php";

$inputData = getJSONData();

$validationResult = RoleValidation::validateSave($inputData);

if (!isset($inputData->id)) {
    $role = Role::createItem($inputData->name, $inputData->label, $inputData->description);
    Response::created()->send();
}

else {
    if (!User::hasPermissions(["edit_roles"])) {
        Response::unauthorized("access_denied")->send();
    }

    $item = Role::getItem("id", $inputData->id);

    if (!$item) {
        Response::badRequest("invalid_id")->send();
    }

    $role = Role::saveItem($inputData->id, $inputData->name, $inputData->label, $inputData->description);
    Response::ok()->send();
}

Response::badRequest("something_went_wrong")->send();