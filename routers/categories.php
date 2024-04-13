<?php

$router->get("/categories", "categories/index");
$router->get("/categories/:id", "categories/show");

$router->post("/categories", "categories/save");

$router->delete("/categories/:id", "categories/delete");
