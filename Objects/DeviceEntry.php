<?php

namespace FsRestApi\Objects;

class DeviceEntry
{

    private $conn;
    private $table_name = "device_entry";
 
    public $entry_id;
    public $device_id;
    public $latitude;
    public $longitude;
    public $reported_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT de.entry_id, de.device_id, de.latitude, de.longitude, "
                . "de.reported_at, d.label "
                . "FROM " . $this->table_name . " de LEFT JOIN device d ON "
                . "de.device_id = d.id "
                . "ORDER BY d.label";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
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
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
