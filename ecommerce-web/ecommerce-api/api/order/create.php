<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../objects/order.php';

$database = new Database();
$db = $database->getConnection();

$oder = new Order($db);

$data =$_POST;


if (!empty($data['user_id']) && !empty($data['product_id']) && !empty($data['quantity'])){
    $oder->user_id = $data['user_id'];
    $oder->product_id = $data['product_id'];
    $oder->quantity = $data['quantity'];
    $oder->total = $data['total'];

    if ($oder->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Order was created."));
    }else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create oder."));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create oder. Data is incomplete."));
}
?>