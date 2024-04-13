<?php

class Media {
    public static function createItem($filename, $type, $size, $src, $options = null) {
        global $database;

        try {
            $database->insert("media", [
                "filename" => $filename,
                "type" => $type,
                "size" => $size,
                "src" => $src,
                "options" => json_encode($options),
            ]);

            return $database->lastInsertedId();
        } catch(Exception $ex) {
            throw new Error("Create media error: " . $ex->getMessage());
        }
    }

    public static function saveItem($id, $options = null) {
        global $database;

        try {
            $database->update("media", [
                "options" => json_encode($options),
            ], "id = $id");
        } catch(Exception $ex) {
            throw new Error("Save media error: " . $ex->getMessage());
        }
    }

    public static function getItems($year = null, $month = null, $day = null) {
        global $database;
        $params = [];

        $sql = "
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m-%d') AS combined_date,
                m.*
            FROM 
                media m
        ";

        if (!empty($year) || !empty($month) || !empty($day)) {
            $sql .= " WHERE";
            $conditions = [];
        
            if (!empty($year)) {
                $conditions[] = "YEAR(created_at) = :year";
                $params[":year"] = $year;
            }
            
            if (!empty($month)) {
                $conditions[] = "MONTH(created_at) = :month";
                $params[":month"] = $month;
            }
            
            if (!empty($day)) {
                $conditions[] = "DAY(created_at) = :day";
                $params[":day"] = $day;
            }
        
            $sql .= " " . implode(" AND ", $conditions);
        }

        try {
            return $database->select($sql, $params);
        } catch(Exception $ex) {
            throw new Error("Fetch all media error: " . $ex->getMessage());
        }
    }

    public static function getItem($column, $value, $fields = "*") {
        global $database;

        try {
            $params = [":$column" => $value];
            return $database->selectSingle("SELECT $fields FROM media WHERE $column = :$column", $params);
        } catch(Exception $ex) {
            throw new Error("Fetch all media error: " . $ex->getMessage());
        }
    }

    public static function deleteItem($id) {
        global $database;

        try {
            $params = [":id" => $id];
            return $database->delete("media", "id = :id", $params);
        } catch(Exception $ex) {
            throw new Error("Fetch all media error: " . $ex->getMessage());
        }
    }

    public static function upload($file) {
        $targetDirectory = "uploads/" . date("Y") . "/" . date("m") . "/" . date("d");
        $targetFile = $targetDirectory . "/" . basename($file["name"]);

        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return ["success" => true, "directory" => $targetDirectory, "file" => $file];
        }
        
        return ["success" => false, "message" => "upload_error"];
    }
}
