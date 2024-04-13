<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Sofia');

define("WEBSITE_TITLE", "");

// general settings
setlocale(LC_MONETARY,"bg");
define("WEBSITE_LANG", "bg");
define("WEBSITE_LINK", "http://localhost");
define("WEBSITE_LANGUAGE_EXTENSION", "bg");
define("WEBSITE_CHARSET", "UTF-8");

// e-mail settings
define("WEBSITE_EMAIL", "");
define("WEBSITE_EMAIL_PASSWORD", "");
define("WEBSITE_EMAIL_HOST", "");

// JWT settings
define("JSON_WEB_TOKEN_EXPIRLY_IN_SECONDS", 3600);
define("JSON_WEB_TOKEN_SECRET_KEY", "");
define("JSON_WEB_TOKEN_SALT", "");

// database settings
define("HOST", "localhost");
define("DATABASE_USER", "");
define("DATABASE_PASSWORD", "");
define("DATABASE_NAME", "");
define("CHARSET", "utf8");

$database = new Database(HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME, CHARSET);
