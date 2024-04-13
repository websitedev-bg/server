<?php

require "middlewares/authenticated.php";
require "middlewares/is-admin.php";

$inputData = getJSONData();

$validationResult = PermissionValidation::validateCreate($inputData);

$role = Role::getItem("id", $inputData->role_id);

if (!$role) {
    Response::badRequest("invalid_role_id")->send();
}

if (isset($inputData->id)) {
    Response::badRequest("dublicate_permission")->send();
}

$permission = Permission::createItem($inputData->name, $inputData->role_id, $inputData->description);
Response::created()->send();
