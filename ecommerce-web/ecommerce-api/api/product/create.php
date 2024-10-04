<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
    http_response_code(200);
    exit();

}
include_once '../../config/database.php';
include_once '../../objects/product.php';

//for security vv:
require '../../vendor/autoload.php';
require '../../config/config.php';
require '../utilities/middleware.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
$key = JWT_SECRET_KEY;
//for security ^^.

$headers = apache_request_headers(); //get response header
$headers = json_decode(json_encode($headers), true);
file_put_contents(LogPath . 'headers.log', print_r($headers, true));

$authHeader = null;

if (isset($headers['Authorization'])){
    $authHeader = $headers['Authorization'];
}elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
}elseif (function_exists('getallheaders')) {
    $allHeaders = getallheaders();
    if(isset($allHeaders['Authorization'])){
        $authHeader = $allHeaders['Authorization'];
    }
}
// file_put_contents('authHeader.log', print_r($authHeader, true));

// check Authorization with token?
if($authHeader === null){
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Authorization header not found."));
    exit();
}

$authHeader = $headers['Authorization'];
$arr = explode (" ", $authHeader);

if(count($arr) !== 2){
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Invalid Authorization header format."));
    exit();
}

$jwt = $arr[1];
// $jwt = $authHeader;
// file_put_contents('jwt.log', print_r($jwt, true));
try {
    // check is admin vv
    // $decoded = JWT::decode($jwt, JWT_SECRET_KEY);
    $decoded = JWT::decode($jwt, new Key(JWT_SECRET_KEY, 'HS256'));
    // $decoded = JWT::decode($jwt, new Key(JWT_SECRET_KEY, 'HS256'), $headers = new stdClass());
    // $decoded = json_decode(json_encode($decoded), true);

    // file_put_contents('decoded.log', print_r($decoded, true));
    //check JWT
    if(empty($decoded)){
        throw new Exception("Invalid token.");
    }

    $role = $decoded->data->role;
    // $role = $decoded['data']['role'];

    if(!checkPermission($role, 'admin')){
        http_response_code(403);
        echo json_encode(array("message" => "Access denied. Insufficient permission."));
    }
    //check is admin ^^

   

        $database = new Database();
        $db = $database->getConnection();

        $product = new Product($db);

        //read the input steam
        $input = file_get_contents("php://input");
        // file_put_contents('input.log', print_r($input, true));

        $data = $_POST; //get formData
        // $data = json_decode(file_get_contents("php://input"), true);
        // file_put_contents('data.log', print_r($data, true));

        if($data === null){
            throw new Exception("Invalid JSON payload.");
        }
        // data by $_POST:
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        // data by "php://input":
        // $product->name = $data->name;
        // $product->description = $data->description;
        // $product->price = $data->price;

        //Handle multiple image uploads
        $imagePaths = []; // array to hold upload file paths
        $uploadDir = '../../uploads/'; // directory to save uploaded files
        // $uploadDirforPath = '/uploads/'; // directory to save uploaded files
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        // Get the current domain
        $currentDomain = $protocol . $_SERVER['HTTP_HOST'];
        // file_put_contents('currentDomain.log', "Faild to up load file: " . $currentDomain);
        echo "Current URL: " . $currentDomain;

        echo "Current domain: " . $currentDomain;
        //Ensure the upload directory exists
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }
        // $images = $data->images; //Assume images is an array of image URLs
        // $images = $data['images']; //Assume images is an array of image URLs

        //Process each uploaded file
        foreach($_FILES['images']['tmp_name'] as $key => $tmp_name){
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetFilePath = $uploadDir . $fileName;

            if(move_uploaded_file($tmp_name, $targetFilePath)) {
                $imagePaths[] =  $currentDomain . str_replace('../..', '', $targetFilePath);
            }else{
                // file_put_contents('uploadFileError.log', "Faild to up load file: " . $fileName);
            }
        }

        // Log the paths of uploaded files
        // file_put_contents('uploadedImages.log', "Uploaded Images: " . print_r($imagePaths, true));


        if ($product->create()){
            $productId = $db->lastInsertId();

            if(!empty($imagePaths)){
                $product->addImages($productId, $imagePaths);
            }else{
                // file_put_contents('impagesAddError.log', "No images to add" );
            }


            http_response_code(201);
            echo json_encode(array("message" => "Product was created."));
        }else{
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create product."));
        }
   
}catch(Exception $e) {
    http_response_code(401);
    echo json_encode(array("message"=> "Access denied. Invalid token", "error" => $e->getMessage()));
}
?>