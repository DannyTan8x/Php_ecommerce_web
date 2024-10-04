<?php 
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;

    public function __construct($db){
        $this->conn = $db;
    }

    public function register() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, password=:password, role=:role";
        $stmt = $this->conn->prepare($query);

        $this->name =htmlspecialchars(strip_tags($this->name));
        $this->email =htmlspecialchars(strip_tags($this->email));
        $this->password =htmlspecialchars(strip_tags($this->password));
        $this->role =htmlspecialchars(strip_tags($this->role));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", "user"); //default as user

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    function login($email, $password){
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $email);
        $stmt->execute();

        $num = $stmt->rowCount();
        if($num > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $password_hash = $row['password'];
            $this->role = $row['role'];

            if(password_verify($password, $password_hash)){
                return true;
            }
        }
        return false;
    }

    function create(){
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, password=:password ";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars((strip_tags($this->name)));
        $this->email = htmlspecialchars((strip_tags($this->email)));
        $this->password = htmlspecialchars((strip_tags($this->password)));
        
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        

        return $stmt->execute();
    }

    function read(){
        $query ="SELECT id, name, email, role FROM " .$this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readOne(){
        $query ="SELECT * FROM " .$this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row){
            $this->name = $row['name'];
            $this->email = $row['email'];
        }
    }

    function update(){
        $query = "UPDATE " . $this->table_name . " SET name=:name, email=:email, password=:password WHERE id =:id";
        // echo $query;
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        return $stmt->execute();
    }
}
?>