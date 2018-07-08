<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../Config/Database.php';
require_once '../Objects/Device.php';

use FsRestApi\Config\Database;
use FsRestApi\Objects\Device;

$database = new Database();
$db = $database->getConnection();
$device = new Device($db);
$stmt = $device->readAll();
$num = $stmt->rowCount();

$devices_arr = [];
$devices_arr['message'] = "No devices found.";

if ($num > 0) {
    $devices_arr['message'] = "{$num} " . ($num === 1 ? "device" : "devices") . " found.";
    $devices_arr['totalRecords'] = $num;
    $devices_arr["records"] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $device_item = [
            "id" => $id,
            "label" => $label
        ];
        array_push($devices_arr["records"], $device_item);
    }
}

echo json_encode($devices_arr);