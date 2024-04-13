<?php

require "middlewares/authenticated.php";

$id = $_GET["id"];

$file = Media::getItem("id", $id);

if (empty($file)) {
    Response::notFound("invalid_id")->send();
}

$inputData = getJSONData();

$updatedFile = Media::saveItem($id, $inputData->options);

Response::ok()->send();