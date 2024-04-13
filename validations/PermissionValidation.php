<?php

class PermissionValidation {
    public static function validateCreate($data) {
        $errors = [];

        if (!isset($data->name) || empty($data->name) || strlen($data->name) < 2) {
            $errors[] = "name_is_required";
        }

        if (!isset($data->role_id) || empty($data->role_id)) {
            $errors[] = "role_id_is_required";
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }
        
        return ["success" => true];
    }
}
