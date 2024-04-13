<?php

require "middlewares/authenticated.php";
require "middlewares/is-admin.php";

$inputData = getJSONData();

$validationResult = CategoryValidation::create($inputData);

if (!isset($inputData->id)) {
    $category = Category::createItem($inputData);
    Response::created($category)->send();
}

else {
    if (!User::hasPermissions(["edit_categories"])) {
        Response::unauthorized("access_denied")->send();
    }

    if (empty($inputData->id)) {
        Response::badRequest("invalid_id")->send();
    }

    $item = Category::getItem("id", $inputData->id);

    if (!$item) {
        Response::badRequest("invalid_id")->send();
    }

    $brand = Category::saveItem($inputData);
    Response::ok()->send();
}

Response::badRequest("something_went_wrong")->send();