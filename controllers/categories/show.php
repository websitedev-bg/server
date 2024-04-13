<?php

$id = $_GET["id"];

if (!isset($id) || empty($id)) {
    Response::badRequest("invalid_id")->send();
}

$item = Category::getItem("id", $id);

if (!$item) {
    Response::badRequest("invalid_id")->send();
}

Response::ok($item)->send();
