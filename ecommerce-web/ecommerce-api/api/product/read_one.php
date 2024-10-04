<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$product->id = isset($_GET['id']) ? $_GET['id'] : die();

$product->readOne();

if($product->name != null){
    $photos_query = "SELECT image_url FROM product_images WHERE product_id = :product_id";
    $photos_stmt = $db->prepare($photos_query);
    $photos_stmt->bindParam(':product_id', $product->id);
    $photos_stmt->execute();
    $photos = $photos_stmt->fetchAll(PDO::FETCH_COLUMN);

    $product_arr = array(
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        // "photos" => $photos,
        "images" => $product->getImages($product->id)
    );
    http_response_code(200);
    echo json_encode($product_arr);
}else{
    http_response_code(404);
    echo json_encode(array("message" => "Product does not exit."));
}
?>