<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../Config/Core.php';
require_once '../Config/Database.php';
require_once '../Objects/Device.php';

use FsRestApi\Config\Core;
use FsRestApi\Config\Database;
use FsRestApi\Objects\Device;

$coreObj = new Core();
$database = new Database();
$db = $database->getConnection();
$device = new Device($db);
$page = (int) filter_input(INPUT_GET, 'page');
$rpp = (int) filter_input(INPUT_GET, 'rpp');
$orderByField = (string) filter_input(INPUT_GET, 'orderbyfield');
$orderBy = (string) filter_input(INPUT_GET, 'orderby');
$coreObj->setPaging($page, $rpp);
$stmt = $device->read($coreObj->fromRecordNum, $coreObj->recordsPerPage, $orderByField, $orderBy);
$num = $stmt->rowCount();

$devices_arr = [];
$devices_arr['message'] = "No devices found.";
$devices_arr['paging'] = [
    'totalRecords' => $device->foundRows(),
    'page' => $coreObj->page,
    'rpp' => $coreObj->recordsPerPage,
    'orderbyfield' => $device->order_by_field,
    'orderby' => $device->order_by
];

if ($num > 0) {
    $devices_arr['message'] = "{$num} " . ($num === 1 ? "device" : "devices") . " found.";
    $devices_arr["records"] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $device_item = [
            "id" => $id,
            "label" => $label,
            "created_at" => $created_at,
            "modified_at" => $modified_at,
            "last_reported_at" => $last_reported_at,
            "latitude" => $latitude,
            "longitude" => $longitude
        ];
        array_push($devices_arr["records"], $device_item);
    }
}

echo json_encode($devices_arr);