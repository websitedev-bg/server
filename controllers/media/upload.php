<?php

require "middlewares/authenticated.php";

$validationResult = MediaValidation::validateUpload($_FILES["file"]);

$file = Media::getItem("filename", $_FILES["file"]["name"]);

if (!empty($file)) {
    Response::badRequest("dublicate_filename")->send();
}

$uploadedFile = Media::upload($_FILES["file"]);

$fileId = Media::createItem(
    $uploadedFile["file"]["name"],
    $uploadedFile["file"]["type"],
    $uploadedFile["file"]["size"],
    $uploadedFile["directory"] . "/" . $uploadedFile["file"]["name"],
    $_FILES["options"] ?? null
);

$file = Media::getItem("id", $fileId);

Response::created($file)->send();
