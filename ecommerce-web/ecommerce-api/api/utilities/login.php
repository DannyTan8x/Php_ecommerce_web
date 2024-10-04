<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../objects/user.php';
require '../../vendor/autoload.php';
require '../../config/config.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// $data = json_decode(file_get_contents("php://input"));
// $email = $data->email;
// $password = $data->password;

$data = $_POST;

$email = $data['email'];
$password = $data['password'];

if($user->login($email, $password)){
    $secret_key = JWT_SECRET_KEY; //use the secret key from config
    $issuer_claim = "http://192.168.1.112:8000"; // this can be the server name
    $audience_claim = "http://192.168.1.112:8000";
    $issuedAt_claim = time();
    $notbefore_claim = $issuedAt_claim + 10; // not valid before 10 seconds
    $expire_claim = $issuedAt_claim + 3600; // expire time in seconds (1 hour)
    $token = array(
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedAt_claim,
        "nbf" => $notbefore_claim,
        "exp" => $expire_claim,
        "data" => array(
            "id" => $user->id,
            "name" => $user->name,
            "email" =>$user->email,
            "role" => $user->role
        )
    );

    http_response_code(200);

    $jwt = JWT::encode($token, $secret_key, 'HS256');
    echo json_encode(
        array(
            "message" => "Successful login.",
            "jwt" => $jwt,
            "role" => $token['data']['role'],
            "expireAt" => $expire_claim
        )
    );
}else{
    http_response_code(401);
    echo json_encode(array("message" => "Login failed."));
}

?>