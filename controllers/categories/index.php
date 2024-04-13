<?php

$parentId = $_GET["parent_id"] ?? null;

$categories = Category::getItems($parentId);

Response::ok($categories)->send();