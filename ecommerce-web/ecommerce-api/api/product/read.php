<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../objects/product.php';

$database = new Database();
$db = $database->getConnection() ;

$product = new Product($db);

$stmt = $product->read();
$num = $stmt->rowCount();

if($num > 0){
     $products_arr = array();
     $products_arr['records'] = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract(($row));

        // // Handle photos
        // $photos_query = "SELECT photo FROM product_photos WHERE product_id = :product_id";
        // $photos_stmt = $db->prepare($photos_query);
        // $photos_stmt->bindParam(':product_id', $id);
        // $photos_stmt->execute();
        // $photos = $photos_stmt->fetchAll(PDO::FETCH_COLUMN);

        
        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            // "photos" => $photos
            "images" => $product->getImages($id)
         );
         array_push($products_arr["records"], $product_item);
    }
    http_response_code(200) ;
    echo json_encode($products_arr);
}else{
    http_response_code(404);
    echo json_encode(array("message" => "No products found."));
}
?>