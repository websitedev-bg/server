<?php

class RoleValidation {
    public static function validateSave($data) {
        $errors = [];

        if (!isset($data->name) || empty($data->name) || strlen($data->name) < 2) {
            $errors[] = "first_name_validation_error";
        }

        if (!isset($data->label) || empty($data->label) || strlen($data->label) < 2) {
            $errors[] = "first_name_validation_error";
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }
        
        return ["success" => true];
    }
}
