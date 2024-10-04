<?php 
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;

    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, description=:description, price=:price";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars((strip_tags($this->name)));
        $this->description = htmlspecialchars((strip_tags($this->description)));
        $this->price = htmlspecialchars((strip_tags($this->price)));
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);

        return $stmt->execute();
    }

    function read(){
        $query ="SELECT id, name, description, price FROM " .$this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readOne(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row){
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            // $this->photo = $row['photo'];
        }
    }

    function update(){
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
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

    public function addImages($productId, $imagePaths){
        foreach($imagePaths as $paths){
            $query = "INSERT INTO product_images SET product_id =:product_id, image_url = :image_url";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":product_id", $productId);
            $stmt->bindParam(":image_url", $paths);
            $stmt->execute();
        }
    }

    public function getImages($productId){
        $query = "SELECT image_url FROM product_images WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>