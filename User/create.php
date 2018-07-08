<?php
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
$userObj->first_name = $data->first_name;
$userObj->last_name = $data->last_name;
$userObj->user_email = $data->user_email;
$userObj->user_pass = md5($data->user_pass);
$userObj->time_zone = $data->time_zone;
$userObj->approved = 0;
$userObj->created_at = gmdate('Y-m-d H:i:s');
$userObj->modified_at = $userObj->created_at;

$message = 'Please provide valid inputs.';
if (!empty($userObj->first_name) && !empty($userObj->last_name) && 
        !empty($userObj->user_email) && !empty($userObj->user_pass) && 
        !empty($userObj->time_zone)) {
    $message = ($userObj->create()) ? 
            "User was created successfully." : 
            "Unable to create user.";
}

echo json_encode(["message" => $message]);