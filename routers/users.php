<?php

$router->get("/users/logged-in", "users/logged-in");

$router->post("/users/register", "users/register");
$router->post("/users/login", "users/login");
$router->post("/users/change-password", "users/change-password");
$router->post("/users/forgot-password", "users/forgot-password");
$router->post("/users/password-recovery", "users/password-recovery");
$router->post("/users/email-confirmation", "users/email-confirmation");
$router->post("/users/update-roles", "users/update-roles");
