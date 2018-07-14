<?php

namespace FsRestApi\Objects;

class Device
{

    private $conn;
    private $table_name = "device";
    private $fields = ["id", "label", "created_at", "modified_at", "last_reported_at", "latitude", "longitude"];
    private $field_alias = ["d.", "d.", "d.", "d.", "", "de.", "de."];
    private $where;
    public $order_by_field;
    public $order_by;

    public $id;
    public $label;
    public $created_at;
    public $modified_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read($conditionalArray = [], $fromRecordNum = 0, $recordsPerPage = 5, $orderByField = "label", $orderBy = "DESC")
    {
        $this->where = [];
        foreach($conditionalArray as $cKey => $cVal) {
            if (!empty($cVal) && in_array($cKey, $this->fields)) {
                $this->where[$cKey] = $this->field_alias[array_search($cKey, $this->fields)] . "{$cKey} = '{$cVal}'"; 
            }
        }
        $whereClause = "";
        if (!empty($this->where)) {
            $whereClause = " WHERE (" . implode(" AND ", $this->where) .") ";
        }

        $this->order_by = (empty($orderBy) || $orderBy != "ASC") ? 
                "DESC" : $orderBy;
        if (!($this->order_by == "ASC" || $this->order_by == "DESC")) {
            $this->order_by = "DESC";
        }
        $this->order_by_field = (!empty($orderByField)) ? 
            (in_array($orderByField, $this->fields) ? 
            $this->field_alias[array_search($orderByField, $this->fields)] . "{$orderByField}" : $orderByField) 
            : "last_reported_at";

        $query = "SELECT SQL_CALC_FOUND_ROWS d.id, d.label, "
                . "d.created_at, d.modified_at, "
                . "MAX(de.reported_at) as last_reported_at, de.latitude, "
                . "de.longitude FROM " . $this->table_name . " d LEFT JOIN "
                . "device_entry de ON d.id = de.device_id " . $whereClause 
                . "GROUP BY d.id ORDER BY " . $this->order_by_field . " "
                . $this->order_by . " LIMIT ?, ?";
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

    public function readAll()
    {
        $query = "SELECT id, label FROM device";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " "
                . "SET label=:label, created_at=:created_at, "
                . "modified_at=:modified_at";
        $stmt = $this->conn->prepare($query);
        $this->label = htmlspecialchars(strip_tags($this->label));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->modified_at = htmlspecialchars(strip_tags($this->modified_at));
        $stmt->bindParam(":label", $this->label);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":modified_at", $this->modified_at);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET label=:label, "
                . "modified_at=:modified_at "
                . "WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->label = htmlspecialchars(strip_tags($this->label));
        $stmt->bindParam(":label", $this->label);
        $stmt->bindParam(":modified_at", $this->modified_at);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

}
