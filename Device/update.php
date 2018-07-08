<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../Config/Database.php';
require_once '../Objects/Device.php';

use FsRestApi\Config\Database;
use FsRestApi\Objects\Device;

$database = new Database();
$db = $database->getConnection();

$device = new Device($db);
$data = json_decode(file_get_contents("php://input"));
$device->id = $data->id;
$device->label = $data->label;
$device->modified_at = gmdate('Y-m-d H:i:s');

$message = 'Please provide valid inputs.';
if (!empty($device->label) && !empty($device->id)) {
    $message = ($device->update()) ? 
            "Device was updated successfully." : 
            "Unable to update device.";
}

echo json_encode(["message" => $message]);