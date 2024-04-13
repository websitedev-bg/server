<?php

$filename = $_GET["filename"];

$file = Media::getItem("filename", str_replace("%20", " ", $filename));

if (empty($file)) {
    Response::notFound("file_not_fount")->send();
}

$file["options"] = json_decode($file["options"]) ?? null;

Response::ok($file)->send();
