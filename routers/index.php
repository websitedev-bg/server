<?php

$router = new Router();

$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

$router->get("/", "index/home");

require "users.php";
require "roles.php";
require "permissions.php";
require "media.php";
require "categories.php";

$router->route($uri, $method);
