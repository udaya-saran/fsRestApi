<?php
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
$device->label = $data->label;
$device->created_at = date('Y-m-d H:i:s');
$device->modified_at = $device->created_at;

$message = 'Please provide valid inputs.';
if (!empty($device->label)) {
    $message = ($device->create()) ? 
            "Device was created successfully." : 
            "Unable to create device.";
}

echo json_encode(["message" => $message]);