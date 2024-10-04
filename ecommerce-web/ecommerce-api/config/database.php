<?php 
class Database {
    private $host = "localhost";
    private $db_name = "ecommerce_db";
    private $usernaem = "root";
    private $password = "";
    public $conn;

    public function getConnection (){
        $this -> conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->usernaem, $this->password);
            $this->conn->exec("set names utf8");
            // echo "connected";//這個字 會出現在 response 的 data: "connected{}" 所以可以考慮拿掉
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>