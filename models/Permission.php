<?php

class Permission {
    public static function createItem($name, $role_id, $description = null) {
        global $database;

        try {
            $database->insert("permissions", [
                "name" => $name,
                "description" => $description,
                "role_id" => $role_id,
            ]);

            return $database->lastInsertedId();
        } catch(Exception $ex) {
            throw new Error("Create permission error: " . $ex->getMessage());
        }
    }

    public static function getItems() {
        global $database;

        try {
            return $database->select("SELECT * FROM permissions");
        } catch(Exception $ex) {
            throw new Error("Fetch all permissions error: " . $ex->getMessage());
        }
    }

    public static function getItemsByRoleId($roleId) {
        global $database;

        try {
            $params = [":role_id" => $roleId];
            return $database->select("SELECT * FROM permissions WHERE role_id = :role_id", $params);
        } catch(Exception $ex) {
            throw new Error("Fetch all permissions by role id error: " . $ex->getMessage());
        }
    }
}