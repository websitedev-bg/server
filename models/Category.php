<?php

class Category {
    public static function createItem($inputData) {
        global $database;

        try {
            $database->insert("categories", [
                "title" => $inputData->title ?? null,
                "description" => $inputData->description ?? null,
                "image" => $inputData->image ?? null,
                "options" => json_encode($inputData->options),
                "meta_options" => json_encode($inputData->meta_options),
                "parent_id" => $inputData->parent_id ?? null,
            ]);

            return self::getItem("id", $database->lastInsertedId());
        } catch(Exception $ex) {
            throw new Error("Create category error: " . $ex->getMessage());
        }
    }

    public static function saveItem($inputData) {
        global $database;

        $id = $inputData->id;

        try {
            $database->update("categories", [
                "title" => $inputData->title,
                "description" => $inputData->description,
                "image" => $inputData->image,
                "meta_options" => json_encode($inputData->meta_options),
            ], "id = $id");
        } catch(Exception $ex) {
            throw new Error("Save category error: " . $ex->getMessage());
        }
    }

    public static function getItems($parentId = null) {
        global $database;

        $params = [];
        $sql = "SELECT * FROM categories";

        if (!empty($parentId)) {
            $params[":parent_id"] = $parentId;
            $sql .= " WHERE parent_id = :parent_id";
        }

        try {
            $items = $database->select($sql, $params);
            
            foreach($items as &$item) {
                $item["options"] = json_decode($item["options"]);
                $item["meta_options"] = json_decode($item["meta_options"]);
            }

            return $items;
        } catch(Exception $ex) {
            throw new Error("Fetch all categories error: " . $ex->getMessage());
        }
    }

    public static function getItem($column, $value, $fields = "*") {
        global $database;

        try {
            $params = [":$column" => $value];
            $item = $database->selectSingle("SELECT $fields FROM categories WHERE $column = :$column", $params);

            if ($item) {
                $item["options"] = json_decode($item["options"]);
                $item["meta_options"] = json_decode($item["meta_options"]);
            }

            return $item;
        } catch(Exception $ex) {
            throw new Error("Fetch all categories error: " . $ex->getMessage());
        }
    }

    public static function deleteItem($id) {
        global $database;

        try {
            $params = [":id" => $id];
            return $database->delete("categories", "id = :id", $params);
        } catch(Exception $ex) {
            throw new Error("Fetch all categories error: " . $ex->getMessage());
        }
    }
}
