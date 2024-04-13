<?php

class User {
    public static function register($email, $password, $username, $options) {
        global $database;

        $token = generateRandomToken();

        Session::saveItem(CONFIRMATION_TOKEN, $token);

        try {
            $result = $database->insert("users", [
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "username" => $username,
                "confirmation_token" => password_hash($token, PASSWORD_DEFAULT),
                "options" => json_encode($options)
            ]);

            if (!$result) {
                return ["success" => false];
            }

            return ["success" => true, "id" => $database->lastInsertedId()];
        } catch(Exception $ex) {
            throw new Error("Register user error: " . $ex->getMessage());
        }
    }

    public static function login($inputData) {
        $item = User::getItem("email", $inputData->email, "password");

        if (!$item) {
            Response::badRequest("invalid_credentials")->send();
        }

        if (!password_verify($inputData->password, $item["password"])) {
            Response::badRequest("invalid_credentials")->send();
        }

        $tokenExpiry = $inputData->remember ? $item["expiry_time"] : JSON_WEB_TOKEN_EXPIRLY_IN_SECONDS;

        $token = self::createToken($inputData->email, $item["password"], $tokenExpiry);

        return $token;
    }

    public static function getItem($column, $value, $fields = "*") {
        global $database;

        try {
            $params = [":$column" => $value];
            $result = $database->selectSingle("SELECT $fields FROM users WHERE $column = :$column", $params);

            if (isset($result["options"])) {
                $result["options"] = json_decode($result["options"]);
            }

            return $result;
        } catch (Exception $ex) {
            throw new Error("Fetch user error: " . $ex->getMessage());
        }
    }

    public static function changePassword($password, $newPassword) {
        global $database;

        try {
            $token = self::getToken();

            $decodedData = self::decodeToken($token);

            if (!isset($decodedData)) {
                Response::badRequest("invalid_token")->send();
            }

            $user = self::getItem("email", $decodedData["email"], "password");
            
            if (!$user) {
                Response::badRequest("invalid_email")->send();
            }
            
            if (!password_verify($password, $user["password"])) {
                Response::badRequest("invalid_password")->send();
            }

            $email = $decodedData["email"];

            $database->update("users", [
                "password" => password_hash($newPassword, PASSWORD_DEFAULT),
            ], "email = '$email'");
        } catch(Exception $ex) {
            throw new Error("Change user password error: " . $ex->getMessage());
        }
    }

    public static function forgotPassword($email) {
        global $database;

        $user = self::getItem("email", $email, "id");
            
        if (!$user) {
            Response::badRequest("invalid_email")->send();
        }

        $resetToken = Session::getItem(RESET_TOKEN_SENDED_KEY);

        if (isset($resetToken) && !empty($resetToken) && $resetToken["time"] > time()) {
            Response::forbidden("already_sended")->send();
        }

        $forgotPasswordToken = generateRandomToken();

        Session::saveItem(RESET_TOKEN, $forgotPasswordToken);

        try {
            $database->update("users", [
                "reset_token" => password_hash($forgotPasswordToken . $user["id"], PASSWORD_DEFAULT),
            ], "email = '$email'");
            
            Session::saveItem(RESET_TOKEN_SENDED_KEY, [
                "time" => time() + RESEND_RESET_TOKEN_TIMEOUT,
            ]);

            return $forgotPasswordToken;
        } catch(Exception $ex) {
            throw new Error("Forgot password error: " . $ex->getMessage());
        }
    }

    public static function passwordRecovery($email, $resetToken, $password) {
        global $database;

        try {
            $user = self::getItem("email", $email, "id, reset_token");

            if (!$user || empty($user["reset_token"])) {
                Response::badRequest("invalid_reset_token")->send();
            }
            
            if (!password_verify($resetToken, $user["reset_token"])) {
                Response::badRequest("invalid_reset_token")->send();
            }

            $database->update("users", [
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "reset_token" => null,
            ], "email = '$email'");
        } catch(Exception $ex) {
            throw new Error("Reset password error: " . $ex->getMessage());
        }
    }

    public static function checkLoginAttempts() {
        $loginAttempts = Session::getItem(MAX_LOGIN_ATTEMPTS_KEY);

        if (!empty($loginAttempts) && $loginAttempts["time"] < time()) {
            Session::deleteItem(MAX_LOGIN_ATTEMPTS_KEY);
            $loginAttempts = null;
        }

        if (!$loginAttempts) {
            Session::saveItem(MAX_LOGIN_ATTEMPTS_KEY, [
                "count" => 1,
                "time" => time() + MAX_LOGIN_ATTEMPTS_TIMEOUT,
            ]);
        }

        else {
            Session::saveItem(MAX_LOGIN_ATTEMPTS_KEY, [
                "count" => $loginAttempts["count"] + 1,
                "time" => $loginAttempts["time"],
            ]);
        }
        
        if (!empty($loginAttempts) && $loginAttempts["count"] >= MAX_LOGIN_ATTEMPTS && $loginAttempts["time"] >= time()) {
            Response::forbidden("login_attempts_exceeded")->send();
        }
    }

    public static function createToken($email, $password, $tokenExpiry = JSON_WEB_TOKEN_EXPIRLY_IN_SECONDS) {
        $jwt = new JsonWebToken(JSON_WEB_TOKEN_SECRET_KEY);
        return $jwt->encode([
            "email" => $email,
            "password" => $password,
            "time" => time()
        ], $tokenExpiry, JSON_WEB_TOKEN_SALT);
    }

    public static function decodeToken($token) {
        if (!$token) {
            return false;
        }

        $jwt = new JsonWebToken(JSON_WEB_TOKEN_SECRET_KEY);
        $decodedData = $jwt->decode($token);

        return $decodedData;
    }

    public static function checkTokenValidity($token) {
        if (!$token) {
            return false;
        }

        $jwt = new JsonWebToken(JSON_WEB_TOKEN_SECRET_KEY);
        $decodedData = $jwt->decode($token);

        if (!isset($decodedData)) {
            return false;
        }

        return true;
    }

    public static function checkTokenExpiry($token) {
        if (!$token) {
            return false;
        }

        $jwt = new JsonWebToken(JSON_WEB_TOKEN_SECRET_KEY);
        $decodedData = $jwt->decode($token);

        if ($decodedData["time"] + $decodedData["exp"] < time()) {
            return false;
        }
        
        return true;
    }

    public static function getToken() {
        if (!isset($_SERVER["HTTP_AUTHORIZATION"]) || empty($_SERVER["HTTP_AUTHORIZATION"])) {
            return false;
        }

        $token = preg_split("/\s+/", $_SERVER["HTTP_AUTHORIZATION"])[1];

        if (!isset($token)) {
            return false;
        }

        return $token;
    }

    public static function updateRoles($userId, $roles) {
        global $database;

        try {
            if (count($roles) > 0) {
                foreach($roles as $roleId) {
                    $role = Role::getItem("id", $roleId, "id");
    
                    if (!$role) {
                        Response::badRequest("invalid_role_id")->send();
                    }
                }
            }

            $database->update("users", [
                "roles" => json_encode($roles),
            ], "id = $userId");
        } catch(Exception $ex) {
            throw new Error("Update role to user error: " . $ex->getMessage());
        }
    }

    public static function isAuthenticated() {
        $token = self::getToken();
        
        if (!self::checkTokenValidity($token)) {
            return false;
        }
        
        if (!self::checkTokenExpiry($token)) {
            return false;
        }
        
        if (!self::checkPassword($token)) {
            return false;
        }

        return true;
    }

    public static function checkPassword($token) {
        if (!$token) {
            return $token;
        }

        $decodedData = self::decodeToken($token);

        $user = self::getItem("email", $decodedData["email"], "password");

        if ($decodedData["password"] !== $user["password"]) {
            return false;
        }
        
        return true;
    }

    public static function isGuest() {
        if (isset($_SERVER["HTTP_AUTHORIZATION"]) && !empty($_SERVER["HTTP_AUTHORIZATION"])) {
            Response::badRequest("access_denied")->send();
        }
        
        return true;
    }

    public static function isInRole($roleId) {
        $token = self::getToken();

        $decodedData = self::decodeToken($token);
        
        $user = self::getItem("email", $decodedData["email"], "role_id");

        if ($user["role_id"] !== $roleId) {
            return false;
        }
        
        return true;
    }

    public static function hasPermissions($neededPermissions = []) {
        $token = self::getToken();
    
        $decodedData = self::decodeToken($token);
    
        $user = self::getItem("email", $decodedData["email"], "role_id");
    
        $hasPermissions = Permission::getItemsByRoleId($user["role_id"]);
    
        return self::checkMatching($neededPermissions, $hasPermissions);
    }
    
    private static function checkMatching($neededPermissions, $hasPermissions) {
        foreach ($neededPermissions as $permission) {
            $permissionFound = false;

            foreach ($hasPermissions as $userPermission) {
                if ($userPermission["name"] === $permission) {
                    $permissionFound = true;
                    break;
                }
            }

            if (!$permissionFound) {
                return false;
            }
        }

        return true;
    }

    public static function generatePassword() {
        return time();
    }

    public static function sendConfirmationEmail($inputData) {
        $token = Session::getItem(CONFIRMATION_TOKEN);
        $confirmation_link = WEBSITE_LINK."/users/email-confirmation?token=$token";
        
        $data = [
            "title" => SUCCESS_REGISTRATION."!",
            "username" => $inputData->username,
            "confirmation_link" => $confirmation_link,
            "website_title" => WEBSITE_TITLE,
          ];
        
        $template = Mail::createTemplate($data, "success-registration");
        
        return Mail::send($inputData->username, SUCCESS_REGISTRATION."!", $inputData->email, $template);
    }

    public static function sendForgotPasswordEmail($user) {
        $resetToken = Session::getItem(RESET_TOKEN);
        $resetLink = WEBSITE_LINK."/users/password-recovery?token=$resetToken";
        
        $data = [
            "title" => LABEL_FORGOT_PASSWORD."!",
            "reset_link" => $resetLink,
            "website_title" => WEBSITE_TITLE,
          ];
        
        $template = Mail::createTemplate($data, "forgot-password");
        
        return Mail::send($user["first_name"], LABEL_FORGOT_PASSWORD."!", $user["email"], $template);
    }
}
