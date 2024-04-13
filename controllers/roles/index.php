<?php

require "middlewares/authenticated.php";
require "middlewares/is-admin.php";

$roles = Role::getItems();

Response::ok($roles)->send();
