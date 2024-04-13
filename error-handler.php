<?php

function errorHandler($exception) {
    $error_message = "[" . date('Y-m-d H:i:s') . "] Exception: {$exception->getMessage()} in {$exception->getFile()} on line {$exception->getLine()}";

    error_log($error_message, 3, "error.log");

    $errors = [
        "message" => $exception->getMessage(),
        "stack" => $exception->getTraceAsString() ?? null,
    ];

    Response::badRequest($errors)->send();
}

set_exception_handler("errorHandler");
