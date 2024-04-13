<?php

require "middlewares/authenticated.php";
require "middlewares/is-admin.php";

$permissions = Permission::getItems();

Response::ok($permissions)->send();
