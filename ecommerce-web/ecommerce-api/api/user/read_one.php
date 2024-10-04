<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);


$user->id = isset($_GET['id']) ? $_GET['id'] : die();

$user->readOne();


if($user->name != null){
    $user_arr = array(
        "id" => $user->id,
        "name" => $user->name,
        "email" => $user->email
    );
    
    http_response_code(200);
    echo json_encode($user_arr);
}else{
    http_response_code(404);
    echo json_encode(array("message" => "No users found."));
}
?>