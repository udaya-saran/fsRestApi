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
$deviceEntry->entry_id = $data->entry_id;

$result = [];
$result['message'] = 'Selected device entry not found.';
if (!empty($deviceEntry->entry_id) && $deviceEntry->entry_id > 0) {
    $result['message'] = ($deviceEntry->delete()) ? 'Device entry was deleted successfully.' : 'Unable to delete device entry.';
}

echo json_encode($result);