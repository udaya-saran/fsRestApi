<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../Config/Database.php';
require_once '../Objects/Device.php';

use FsRestApi\Config\Database;
use FsRestApi\Objects\Device;

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
$device = new Device($db);
$keyword = (string) filter_input(INPUT_GET, 's');
$stmt = $device->search($keyword);
$num = $stmt->rowCount();
$devices_arr = ["message" => "No devices found."];
if ($num > 0) {
    $message = "{$num} " . ($num === 1 ? "device" : "devices") . " found.";
    $devices_arr = ["message" => $message];
    $devices_arr["records"] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $device_item = [
            "id" => $id,
            "label" => $label,
            "last_reported_at" => $last_reported_at,
            "latitude" => $latitude,
            "longitude" => $longitude
        ];
        array_push($devices_arr["records"], $device_item);
    }
}

echo json_encode($devices_arr);