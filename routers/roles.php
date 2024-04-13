<?php

$router->get("/roles", "roles/index");

$router->post("/roles", "roles/save");

$router->delete("/roles/:id", "roles/delete");