<?php

namespace FsRestApi\Objects;

class User
{

    private $conn;
    private $table_name = "user";
 
    public $user_id;
    public $first_name;
    public $last_name;
    public $user_email;
    public $user_pass;
    public $time_zone;
    public $approved;
    public $created_at;
    public $modified_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT user_id, first_name, last_name, user_email, time_zone, "
                . "approved, created_at, modified_at "
                . "FROM " . $this->table_name . " ORDER BY first_name, last_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET "
                . "first_name=:first_name, last_name=:last_name, "
                . "user_email=:user_email, user_pass=:user_pass, "
                . "time_zone=:time_zone, approved=:approved, "
                . "created_at=:created_at, modified_at=:modified_at";
        $stmt = $this->conn->prepare($query);
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->user_email = htmlspecialchars(strip_tags($this->user_email));
        $this->user_pass = htmlspecialchars(strip_tags($this->user_pass));
        $this->time_zone = htmlspecialchars(strip_tags($this->time_zone));
        $this->approved = htmlspecialchars(strip_tags($this->approved));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->modified_at = htmlspecialchars(strip_tags($this->modified_at));
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":user_email", $this->user_email);
        $stmt->bindParam(":user_pass", $this->user_pass);
        $stmt->bindParam(":time_zone", $this->time_zone);
        $stmt->bindParam(":approved", $this->approved);
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
        $query = "UPDATE " . $this->table_name . " SET "
                . "first_name=:first_name, last_name=:last_name, "
                . "user_email=:user_email, time_zone=:time_zone "
                . "WHERE user_id=:user_id";
        $stmt = $this->conn->prepare($query);
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->user_email = htmlspecialchars(strip_tags($this->user_email));
        $this->time_zone = htmlspecialchars(strip_tags($this->time_zone));
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":user_email", $this->user_email);
        $stmt->bindParam(":time_zone", $this->time_zone);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return true;
        }
        return false;
    }

}