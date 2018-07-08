<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../Config/Database.php';
require_once '../Objects/DeviceEntry.php';

use FsRestApi\Config\Database;
use FsRestApi\Objects\DeviceEntry;

$database = new Database();
$db = $database->getConnection();

$deviceEntry = new DeviceEntry($db);
$data = json_decode(file_get_contents("php://input"));
$deviceEntry->device_id = $data->device_id;
$deviceEntry->latitude = $data->latitude;
$deviceEntry->longitude = $data->longitude;
$deviceEntry->reported_at = date('Y-m-d H:i:s');

$message = 'Please provide valid inputs.';
if (!empty($deviceEntry->device_id) && !empty($deviceEntry->latitude) && !empty($deviceEntry->longitude)) {
    $message = ($deviceEntry->create()) ? 
            "Device entry was created successfully." : 
            "Unable to create device entry.";
}

echo json_encode(["message" => $message]);