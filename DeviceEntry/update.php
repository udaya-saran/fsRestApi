<?php
// required headers
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
$deviceEntry->entry_id = $data->entry_id;
$deviceEntry->device_id = $data->device_id;
$deviceEntry->latitude = $data->latitude;
$deviceEntry->longitude = $data->longitude;

$message = 'Please provide valid inputs.';
if (!empty($deviceEntry->entry_id) && !empty($deviceEntry->device_id) && 
        !empty($deviceEntry->latitude) && !empty($deviceEntry->longitude)) {
    $message = ($deviceEntry->update()) ? 
            "Device entry was updated successfully." : 
            "Unable to update device entry.";
}

echo json_encode(["message" => $message]);