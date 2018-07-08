<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../Config/Database.php';
require_once '../Objects/DeviceEntry.php';

use FsRestApi\Config\Database;
use FsRestApi\Objects\DeviceEntry;

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
$deviceEntry = new DeviceEntry($db);
$stmt = $deviceEntry->read();
$num = $stmt->rowCount();

$deviceEntries_arr = ["message" => "No device entries found."];
if ($num > 0) {
    $message = "{$num} device " . ($num === 1 ? "entry" : "entries") . " found.";
    $deviceEntries_arr = ["message" => $message];
    $deviceEntries_arr["records"] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $deviceEntry_item = [
            "entry_id" => $entry_id,
            "device_id" => $device_id,
            "label" => $label,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "reported_at" => $reported_at
        ];
        array_push($deviceEntries_arr["records"], $deviceEntry_item);
    }
}

echo json_encode($deviceEntries_arr);
