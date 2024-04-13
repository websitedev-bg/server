<?php

require "middlewares/authenticated.php";

$id = $_GET["id"];

$file = Media::getItem("id", $id, "id, src");

if (!$file) {
    Response::notFound("invalid_id")->send();
}

unlink($file["src"]);

Media::deleteItem($id);

Response::ok()->send();