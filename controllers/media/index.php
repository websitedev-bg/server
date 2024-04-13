<?php

$year = $_GET["year"] ?? null;
$month = $_GET["month"] ?? null;
$day = $_GET["day"] ?? null;

$files = Media::getItems($year, $month, $day);

foreach($files as &$file) {
    $file["options"] = json_decode($file["options"]) ?? null;
}

Response::ok($files)->send();
