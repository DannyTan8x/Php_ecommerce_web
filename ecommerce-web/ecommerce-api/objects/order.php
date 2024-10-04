<?php 
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $user_id;
    public $product_id;
    public $quantity;
    public $total;

    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
        $query = "INSERT INTO " . $this->table_name . "SET user_id=:user_id, product_id=:product_id. quantity=:quantity, total=:total";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars((strip_tags($this->user_id)));
        $this->product_id = htmlspecialchars((strip_tags($this->product_id)));
        $this->quantity = htmlspecialchars((strip_tags($this->quantity)));
        $this->total = htmlspecialchars((strip_tags($this->total)));
        
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":total", $this->total);

        return $stmt->execute();
    }

    function read(){
        $query ="SELECT id, user_id, product_id quantity, total, created_at FROM " .$this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function update(){
        $query = "UPDATE " . $this->table_name . "SET user_id=:user_id, product_id=:product_id, quantity=:quantity, total=:total WHERE id =:id";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->total = htmlspecialchars(strip_tags($this->total));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    function delete(){
        $query = "DELETE FROM " . $this->table_name . "WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        return $stmt->execute();
    }
}
?>