<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../../config/database.php';
include_once '../../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$data = json_decode(file_get_contents("php://input"));

$product->id = $data->id;
$product->name = $data->name;
$product->description = $data->description;
$product->price = $data->price;

if($product->update()){
    $product_id = $product->id;

    //Handle multiple photo uploads
    if(isset($_FILES['photos'])){
        $photo_count = count($_FILES['photos']['name']);
        $photo_paths = [];
    
        for($i = 0; $i < $photo_count; $i++){
            $photo_name = basename($_FILES['photos']['name'][$i]);
            $target_dir = "../../uploads";
            $target_file = $target_dir . $photo_name;
    
            if(move_uploaded_file($_FILES['photos']['tmp_name'][$i], $target_file)){
                $photo_paths[] = $photo_name;
    
                //Insert photo path into product_photo table
                $query = "INSERT INTO product_photos (product_id, photo) VALUES (:product_id, :photo)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->bindParam(':photo', $photo_name);
                $stmt->execute();
            }
        }

    }

    http_response_code(200);
    echo json_encode(array("message" => "product was updated."));
}else{
    http_response_code(503);
    echo json_encode(array("message"=>"Unable to update product."));
}
?>