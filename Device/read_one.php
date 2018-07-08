<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

require_once '../Config/Database.php';
require_once '../Objects/Device.php';

use FsRestApi\Config\Database;
use FsRestApi\Objects\Device;

$database = new Database();
$db = $database->getConnection();

$device = new Device($db);
$device->id = (int) filter_input(INPUT_GET, 'id');
$stmt = $device->readOne();
$num = $stmt->rowCount();
$devices_arr = ["message" => "No devices found."];
if ($num == 1) {
    $message = "{$num} device found.";
    $devices_arr = ["message" => $message];
    $devices_arr["records"] = [];
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
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

echo json_encode($devices_arr);