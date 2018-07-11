<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../Config/Core.php';
require_once '../Config/Database.php';
require_once '../Objects/DeviceEntry.php';

use FsRestApi\Config\Core;
use FsRestApi\Config\Database;
use FsRestApi\Objects\DeviceEntry;

$coreObj = new Core();
$database = new Database();
$db = $database->getConnection();
$deviceEntry = new DeviceEntry($db);

$conditionalArray = [];
$conditionalArray["entry_id"] = (int) filter_input(INPUT_GET, 'entry_id');
$conditionalArray["device_id"] = (int) filter_input(INPUT_GET, 'device_id');

$page = (int) filter_input(INPUT_GET, 'page');
$rpp = (int) filter_input(INPUT_GET, 'rpp');
$tiZo = (int) filter_input(INPUT_GET, 'tiZo');
$tiZo = ($tiZo == 0) ? 0 : $tiZo * -1;
$userTiZo = timezone_name_from_abbr("", (int) $tiZo * 60, false);

$orderByField = (string) filter_input(INPUT_GET, 'orderbyfield');
$orderBy = (string) filter_input(INPUT_GET, 'orderby');
$coreObj->setPaging($page, $rpp);
$stmt = $deviceEntry->read($conditionalArray, $coreObj->fromRecordNum, $coreObj->recordsPerPage, $orderByField, $orderBy);
$num = $stmt->rowCount();

$deviceEntries_arr = [];
$deviceEntries_arr['message'] = "No device entries found.";
$deviceEntries_arr['paging'] = [
    'totalRecords' => $deviceEntry->foundRows(),
    'page' => $coreObj->page,
    'rpp' => $coreObj->recordsPerPage,
    'orderbyfield' => $deviceEntry->order_by_field,
    'orderby' => $deviceEntry->order_by,
    'tiZo' => $tiZo,
    'timeZone' => $userTiZo
];

if ($num > 0) {
    $message = "{$num} device " . ($num === 1 ? "entry" : "entries") . " found.";
    $deviceEntries_arr["message"] = $message;
    $deviceEntries_arr["records"] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $reported_at = ($reported_at !== null) ? $coreObj->convertUtcToCurrentTz($reported_at, $userTiZo) : $reported_at;
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
