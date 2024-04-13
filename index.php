<?php

require "error-handler.php";

require 'classes/Autoloader.php';
Autoloader::register();

session_start();

$allowed_languages = ["bg", "en"];
$language = $_SESSION["language"] ?? "bg";

require "languages/" . $language . ".php";

require "constants.php";
require "common/functions.php";
require "config.php";

require "routers/index.php";
