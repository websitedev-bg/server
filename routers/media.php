<?php

$router->get("/media/:filename", "media/show");
$router->get("/media", "media/index");

$router->post("/media/upload", "media/upload");
$router->post("/media/:id", "media/update");

$router->delete("/media/:id", "media/delete");
