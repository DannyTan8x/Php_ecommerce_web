<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../../config/database.php';
include_once '../../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->id = $data->id;

$user->name = $data->name;
$user->email = $data->email;
$user->password = password_hash($data->password, PASSWORD_BCRYPT);

if($user->update()){
    http_response_code(200);
    echo json_encode(array("message" => "User was updated."));
}else{
    http_response_code(503);
    echo json_encode(array("message"=>"Unable to update user."));
}
?>