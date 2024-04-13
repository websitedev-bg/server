<?php

require "middlewares/authenticated.php";
require "middlewares/is-admin.php";

$id = $_GET["id"];

if (!isset($id) || empty($id)) {
    Response::badRequest("invalid_id")->send();
}

$item = Role::getItem("id", $id);

if (!$item) {
    Response::badRequest("invalid_id")->send();
}

Role::deleteItem($id);

Response::ok()->send();