<?php

class CategoryValidation {
    public static function create($data) {
        $errors = [];

        if (!isset($data->title) || empty($data->title)) {
            $errors[] = "title_is_required";
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }

        return ["success" => true];
    }
}