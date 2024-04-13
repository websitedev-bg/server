<?php

function controller($templateName, $data = array())
{
  extract($data);
  require 'controllers/' . $templateName . '.php';
}

function validateJSONData($inputData = null)
{
  if ($inputData === null && json_last_error() !== JSON_ERROR_NONE) {
    Response::badRequest("Error decoding JSON data")->send();
  }
}

function getJSONData() {
  $inputData = json_decode(file_get_contents("php://input"));
  validateJSONData($inputData);
  return $inputData;
}

function generateRandomToken($length = 64) {
  $randomBytes = random_bytes($length);
  $token = bin2hex($randomBytes);
  return $token;
}