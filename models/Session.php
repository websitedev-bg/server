<?php

class Session {
    public static function saveItem($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function getItem($key) {
        return $_SESSION[$key] ?? false;
    }
    
    public static function deleteItem($key) {
        unset($_SESSION[$key]);
    }
}
