<?php

$jsonData = ["Hello, World!"];

User::isAuthenticated();

Response::ok($jsonData)->send();