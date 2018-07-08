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
$userObj->user_id = $data->user_id;

$message = 'Selected user not found.';
if (!empty($userObj->user_id) && $userObj->user_id > 0) {
    $message = ($userObj->delete()) ? 'User was deleted successfully.' : 'Unable to delete user.';
}

echo json_encode(["message" => $message]);