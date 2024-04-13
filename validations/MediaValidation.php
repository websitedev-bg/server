<?php

class MediaValidation {
    public static function validateUpload($data) {
        if (!isset($data->name) || empty($data->name)) {
            $errors[] = "invalid_file";
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }

        return ["success" => true];
    }
}
