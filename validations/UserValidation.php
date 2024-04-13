<?php

class UserValidation {
    public static function validateRegister($email, $password, $cpassword, $username) {
        $errors = [];

        if (!isset($email) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "email_validation_error";
        }

        if (!isset($password) || empty($password) || strlen($password) < 6) {
            $errors[] = "password_validation_error";
        }

        if ($password !== $cpassword) {
            $errors[] = "passwords_match_error";
        }

        if (!isset($username) || empty($username)) {
            $errors[] = "username_is_required";
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }
        
        return ["success" => true];
    }

    public static function validateLogin($inputData) {
        $errors = [];

        if (!isset($inputData->email) || empty($inputData->email) || !filter_var($inputData->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "email_validation_error";
        }

        if (!isset($inputData->password) || empty($inputData->password) || strlen($inputData->password) < 6) {
            $errors[] = "password_validation_error";
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }
        
        return ["success" => true];
    }

    public static function validateChangePassword($data) {
        $errors = [];

        if (!isset($data->password) || empty($data->password) || strlen($data->password) < 6) {
            $errors[] = "password_validation_error";
        }
        
        if (!isset($data->new_password) || empty($data->new_password) || strlen($data->new_password) < 6) {
            $errors[] = "password_validation_error";
        }
        
        if ($data->new_password !== $data->cnew_password) {
            $errors[] = "passwords_match_error";
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }
        
        return ["success" => true];
    }

    public static function validateForgotPassword($data) {
        $errors = [];

        if (!isset($data->email) || empty($data->email)) {
            $errors[] = "invalid_email_address";
        }

        else if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "invalid_email_address";
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }

        return ["success" => true];
    }
    
    public static function validatePasswordRecovery($data) {
        $errors = [];

        if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "invalid_email_address";
        }

        if (!isset($data->reset_token) || empty($data->reset_token)) {
            $errors[] = "invalid_reset_token";
        }

        if (!isset($data->password) || empty($data->password) || strlen($data->password) < 6) {
            $errors[] = "password_validation_error";
        }

        if ($data->password !== $data->cpassword) {
            $errors[] = "passwords_match_error";
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }

        return ["success" => true];if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }

        return ["success" => true];
    }

    public static function validateUpdateRoles($data) {
        $errors = [];

        if (!isset($data->roles) || !is_array($data->roles)) {
            Response::badRequest("invalid_role_id")->send();
        }

        if (count($errors) > 0) {
            return ["success" => false, "errors" => $errors];
        }

        return ["success" => true];
    }
}
