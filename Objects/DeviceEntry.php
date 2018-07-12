<?php

namespace FsRestApi\Objects;

class DeviceEntry
{

    private $conn;
    private $table_name = "device_entry";
    private $fields = ["entry_id", "device_id", "latitude", "longitude", "reported_at", "label"];
    private $field_alias = ["de", "de", "de", "de", "de", "d"];
    private $where;
    public $order_by_field;
    public $order_by;
 
    public $entry_id;
    public $device_id;
    public $latitude;
    public $longitude;
    public $reported_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read($conditionalArray = [], $fromRecordNum = 0, $recordsPerPage = 5, $orderByField = "label", $orderBy = "ASC")
    {
        $this->where = [];
        foreach($conditionalArray as $cKey => $cVal) {
            if (!empty($cVal) && in_array($cKey, $this->fields)) {
            $this->where[$cKey] = $this->field_alias[array_search($cKey, $this->fields)] . ".{$cKey} = '{$cVal}'"; 
            }
        }
        $whereClause = "";
        if (!empty($this->where)) {
            $whereClause = " WHERE (" . implode(" AND ", $this->where) .") ";
        }

        $this->order_by = (empty($orderBy) || $orderBy != "DESC") ? 
                "ASC" : $orderBy;
        $this->order_by_field = (!empty($orderByField)) ? 
            (in_array($orderByField, $this->fields) ? 
            $this->field_alias[array_search($orderByField, $this->fields)] . ".{$orderByField}" : $orderByField) 
            : "d.label";

        $query = "SELECT SQL_CALC_FOUND_ROWS de.entry_id, de.device_id, "
                . "de.latitude, de.longitude, "
                . "de.reported_at, d.label "
                . "FROM " . $this->table_name . " de LEFT JOIN device d ON "
                . "de.device_id = d.id " . $whereClause 
                . "ORDER BY " . $this->order_by_field . " "
                . $this->order_by . " LIMIT ?, ?";
        file_put_contents("/tmp/query.log", $query);
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $fromRecordNum, \PDO::PARAM_INT);
        $stmt->bindParam(2, $recordsPerPage, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function foundRows()
    {
        $query = "SELECT FOUND_ROWS() as totalCount";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row['totalCount'];
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET "
                . "device_id=:device_id, latitude=:latitude, "
                . "longitude=:longitude, reported_at=:reported_at";
        $stmt = $this->conn->prepare($query);
        $this->device_id = htmlspecialchars(strip_tags($this->device_id));
        $this->latitude = htmlspecialchars(strip_tags($this->latitude));
        $this->longitude = htmlspecialchars(strip_tags($this->longitude));
        $this->reported_at = htmlspecialchars(strip_tags($this->reported_at));
        $stmt->bindParam(":device_id", $this->device_id);
        $stmt->bindParam(":latitude", $this->latitude);
        $stmt->bindParam(":longitude", $this->longitude);
        $stmt->bindParam(":reported_at", $this->reported_at);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET device_id=:device_id, "
                . "latitude=:latitude, longitude=:longitude "
                . "WHERE entry_id=:entry_id";
        $stmt = $this->conn->prepare($query);
        $this->device_id = htmlspecialchars(strip_tags($this->device_id));
        $this->latitude = htmlspecialchars(strip_tags($this->latitude));
        $this->longitude = htmlspecialchars(strip_tags($this->longitude));
        $this->entry_id = htmlspecialchars(strip_tags($this->entry_id));
        $stmt->bindParam(":device_id", $this->device_id);
        $stmt->bindParam(":latitude", $this->latitude);
        $stmt->bindParam(":longitude", $this->longitude);
        $stmt->bindParam(":entry_id", $this->entry_id);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE entry_id = ?";
        $stmt = $this->conn->prepare($query);
        $this->entry_id = htmlspecialchars(strip_tags($this->entry_id));
        $stmt->bindParam(1, $this->entry_id);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

}