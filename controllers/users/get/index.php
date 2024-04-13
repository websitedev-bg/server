<?php

require "middlewares/authenticated.php";

if (!User::hasPermissions(["view_users"])) {
    Response::badRequest("access_denied")->send();
}

$limit = $_GET["limit"] ?? null;
$page = $_GET["page"] ?? null;
$offset = $page <= 0 ? 0 : ($page - 1) * $limit;

$requiredColumns = "id, email, username, expiry_time, confirmation_token, reset_token, options, created_at, role_id";

$items = User::getItems($limit, $offset, $requiredColumns);

foreach($items as &$item) {
    $item["options"] = json_decode($item["options"]);
}

Response::ok($items)->send();
