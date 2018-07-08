<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../Config/Database.php';
require_once '../Objects/User.php';

use FsRestApi\Config\Database;
use FsRestApi\Objects\User;

$database = new Database();
$db = $database->getConnection();
$userObj = new User($db);
$stmt = $userObj->read();
$num = $stmt->rowCount();

$users_arr = ["message" => "No device entries found."];
if ($num > 0) {
    $message = "{$num} " . ($num === 1 ? "user" : "users") . " found.";
    $users_arr = ["message" => $message];
    $users_arr["records"] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $userObj_item = [
            "user_id" => $user_id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "user_email" => $user_email,
            "time_zone" => $time_zone,
            "approved" => $approved,
            "reported_at" => $created_at,
            "modified_at" => $modified_at
        ];
        array_push($users_arr["records"], $userObj_item);
    }
}

echo json_encode($users_arr);