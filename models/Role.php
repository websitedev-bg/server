<?php

class Role {
    public static function createItem($name, $label, $description = null) {
        global $database;

        try {
            $database->insert("roles", [
                "name" => $name,
                "label" => $label,
                "description" => $description,
            ]);

            return $database->lastInsertedId();
        } catch(Exception $ex) {
            throw new Error("Create role error: " . $ex->getMessage());
        }
    }

    public static function saveItem($id, $name, $label, $description = null) {
        global $database;

        try {
            $database->update("roles", [
                "name" => $name,
                "label" => $label,
                "description" => $description,
            ], "id = $id");
        } catch(Exception $ex) {
            throw new Error("Save role error: " . $ex->getMessage());
        }
    }

    public static function getItems() {
        global $database;

        try {
            return $database->select("SELECT * FROM roles");
        } catch(Exception $ex) {
            throw new Error("Fetch all roles error: " . $ex->getMessage());
        }
    }

    public static function getItem($column, $value, $fields = "*") {
        global $database;

        try {
            $params = [":$column" => $value];
            return $database->select("SELECT $fields FROM roles WHERE $column = :$column", $params);
        } catch(Exception $ex) {
            throw new Error("Fetch all roles error: " . $ex->getMessage());
        }
    }

    public static function deleteItem($id) {
        global $database;

        try {
            $params = [":id" => $id];
            return $database->delete("roles", "id = :id", $params);
        } catch(Exception $ex) {
            throw new Error("Fetch all roles error: " . $ex->getMessage());
        }
    }
}
