<?php

namespace FsRestApi\Objects;

class Device
{

    private $conn;
    private $table_name = "device";
 
    public $id;
    public $label;
    public $created_at;
    public $modified_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT d.id, d.label, d.created_at, d.modified_at "
                . "FROM " . $this->table_name . " d ORDER BY d.label";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readLastReported()
    {
        $query = "SELECT d.id, d.label, "
                . "MAX(de.reported_at) as last_reported_at, de.latitude, "
                . "de.longitude FROM " . $this->table_name . " d LEFT JOIN "
                . "device_entry de ON d.id = de.device_id "
                . "GROUP BY d.id ORDER BY d.label";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function readPaging($fromRecordNum = 0, $recordsPerPage = 5)
    {
        $query = "SELECT d.id, d.label, "
                . "MAX(de.reported_at) as last_reported_at, de.latitude, "
                . "de.longitude FROM " . $this->table_name . " d LEFT JOIN "
                . "device_entry de ON d.id = de.device_id "
                . "GROUP BY d.id ORDER BY d.label "
                . "LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $fromRecordNum, \PDO::PARAM_INT);
        $stmt->bindParam(2, $recordsPerPage, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function totalCount()
    {
        $query = "SELECT COUNT(*) AS totalCount FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row['totalCount'];
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
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne()
    {
        $query = "SELECT d.id, d.label, "
                . "MAX(de.reported_at) as last_reported_at, de.latitude, "
                . "de.longitude FROM " . $this->table_name . " d LEFT JOIN "
                . "device_entry de ON d.id = de.device_id "
                . "WHERE d.id = ? LIMIT 0, 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET label=:label "
                . "WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->label = htmlspecialchars(strip_tags($this->label));
        $stmt->bindParam(":label", $this->label);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
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
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function search($keyword = "")
    {
        $query = "SELECT d.id, d.label, "
                . "MAX(de.reported_at) as last_reported_at, de.latitude, "
                . "de.longitude FROM " . $this->table_name . " d LEFT JOIN "
                . "device_entry de ON d.id = de.device_id "
                . "WHERE d.label LIKE ? "
                . "GROUP BY d.id ORDER BY d.label";
        $stmt = $this->conn->prepare($query);
        $keyword = htmlspecialchars(strip_tags($keyword));
        $keyword = "%{$keyword}%";
        $stmt->bindParam(1, $keyword);
        $stmt->execute();
        return($stmt);
    }

}
