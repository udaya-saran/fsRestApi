<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../Config/Core.php';
require_once '../Shared/Utilities.php';
require_once '../Config/Database.php';
require_once '../Objects/Device.php';

use FsRestApi\Config\Core;
use FsRestApi\Shared\Utilities;
use FsRestApi\Config\Database;
use FsRestApi\Objects\Device;

$coreObj = new Core();
$utilitiesObj = new Utilities();
$database = new Database();
$db = $database->getConnection();
$device = new Device($db);
$page = (int) filter_input(INPUT_GET, 'page');
$coreObj->setPage($page);
$stmt = $device->readPaging($coreObj->fromRecordNum, Core::$recordsPerPage);
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

    $totalRows = $device->totalCount();
    $devices_arr["totalCount"] = $totalRows;
    $pageUrl = Core::$homeUrl . "Device/read_paging.php?";
    $devices_arr["paging"] = $utilitiesObj->getPaging($coreObj->page, $totalRows, Core::$recordsPerPage, $pageUrl);
}

echo json_encode($devices_arr);