<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../Config/Database.php';
require_once '../Objects/User.php';

use FsRestApi\Config\Database;
use FsRestApi\Objects\User;

$database = new Database();
$db = $database->getConnection();

$userObj = new User($db);
$data = json_decode(file_get_contents("php://input"));
$userObj->user_id = $data->user_id;
$userObj->first_name = $data->first_name;
$userObj->last_name = $data->last_name;
$userObj->user_email = $data->user_email;
$userObj->time_zone = $data->time_zone;
$userObj->modified_at = gmdate('Y-m-d H:i:s');

$message = 'Please provide valid inputs.';
if (!empty($userObj->user_id) && !empty($userObj->first_name) && 
        !empty($userObj->last_name) && !empty($userObj->user_email) && 
        !empty($userObj->time_zone)) {
    $message = ($userObj->update()) ? 
            "User was updated successfully." : 
            "Unable to update user.";
}

echo json_encode(["message" => $message]);