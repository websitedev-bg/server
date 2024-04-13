<?php

$router->get("/permissions", "permissions/index");

$router->post("/permissions", "permissions/save");
