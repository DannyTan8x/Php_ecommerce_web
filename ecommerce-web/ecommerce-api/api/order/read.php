<?php
header("Access-Control-Allow-Origin: 8");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../objects/order.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

$stmt = $order->read();
$num = $stmt->rowCount();


if($num > 0){
    $order_arr = array();
    $order_arr["records"] = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $order_item = array(
            "id" => $id,
            "user_id" => $user_id,
            "product_id" => $product_id,
            "quantity" => $quantity,
            "total" => $total,
            "created_at" => $created_at
        );
        array_push($order_arr["records"], $order_item);
    }
    http_response_code(200);
    echo json_encode($order_arr);
}else{
    http_response_code(404);
    echo json_encode(array("message" => "No orders found."));
}
?>