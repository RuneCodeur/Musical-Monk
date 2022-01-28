<?php
include_once('model/connectDB.php');

final class Product extends ConnectDB {

    public function SearchProduct (int $type, string $search): ?array {
        if($type == '0' AND $search == ''){
            $response = $this->SearchAll();
        }
        else if($type != '0' AND $search == ''){
            $response = $this->SearchByType($type);
        }
        else if($type == '0' AND $search != ''){
            $response = $this->SearchByWords($search);
        }
        else if($type != '0' AND $search != ''){
            $response = $this->SearchByTypeAndWords($type, $search);
        }
        return $response;
    }

    private function SearchAll() {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT product.id as id, product.name as name, product_type.name as type, product.price as price FROM product INNER JOIN product_type ON product.type = product_type.id');
        $req ->execute();
        $response = $req->fetchAll();
        if($response == false){
            return null;
        }else{
            return $response;
        }
    }
    
    private function SearchByType(int $type) {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT product.id as id, product.name as name, product_type.name as type, product.price as price FROM product INNER JOIN product_type ON product.type = product_type.id WHERE product.type = :type');
        $req ->execute(array('type' => $type));
        $response = $req->fetchAll();
        if($response == false){
            return null;
        }else{
            return $response;
        }
    }
    
    private function SearchByWords(string $search) {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT product.id as id, product.name as name, product_type.name as type, product.price as price FROM product INNER JOIN product_type ON product.type = product_type.id WHERE MATCH (product.name, product.description) AGAINST(:value)');
        $req ->execute(array('value' =>'FRENCH "' . str_replace(' ', '","', htmlspecialchars($search)) .'"'));
        $response = $req->fetchAll();
        if($response == false){
            return null;
        }else{
            return $response;
        }

    }
    
    private function SearchByTypeAndWords(int $type, string $search) {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT product.id as id, product.name as name, product_type.name as type, product.price as price FROM product INNER JOIN product_type ON product.type = product_type.id WHERE MATCH (product.name, product.description) AGAINST(:value) AND product.type = :type');
        $req ->execute(array('value' =>'FRENCH "' . str_replace(' ', '","', htmlspecialchars($search)) .'"','type' => $type));
        $response = $req->fetchAll();
        if($response == false){
            return null;
        }else{
            return $response;
        }
    }

    public function AllTypeProduct() : ?array { 
        $bdd = parent::Connection();
        $req = $bdd -> prepare('SELECT * FROM product_type');
        $req -> execute();
        $response = $req->fetchall();
        if($response == false){
            return null;
        }else{
            return $response;
        }
    }

    public function RandomProduct() : ?array {
        $bdd = parent::Connection();
        $req = $bdd -> prepare('SELECT id, name, price, picture FROM product WHERE quantity > 0 ORDER BY RAND() LIMIT 1');
        $req -> execute();
        $response = $req->fetch();
        if($response == false){
            return null;
        }else{
            return $response;
        }
    }
    
    public function OneProduct($id): ?array {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT product.name as name, product_type.name as type, product.description as description, product.picture as picture, product.quantity as quantity, product.price as price FROM product INNER JOIN product_type ON product.type = product_type.id WHERE product.id = :id');
        $req ->execute(array('id' => $id));
        $response = $req->fetch();
        if($response === false){
            return null;
        }
        else{
            return $response;
        }
    }
    
    public function ProductReserved(int $user): ?array {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT product.id as product, product.name as name, product.price as price, reserved_product.id as id, reserved_product.quantity as quantity FROM reserved_product INNER JOIN product ON reserved_product.product = product.id WHERE reserved_product.user = :user ');
        $req ->execute(array('user' => $user));
        $response = $req->fetchall();
        if($response === false){
            return null;
        }
        else{
            return $response;
        }
    }

    public function Reservation(int $user, int $product, int $quantity): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT quantity FROM product WHERE id = :id');
        $req ->execute(array('id' => $product));
        $remains = $req->fetch();
        if($remains === false){
            throw new Exception("Ce produit n'existe plus.");
        }
        if(($remains['quantity'] - $quantity) < 0 ){
            throw new Exception("Vous ne pouvez pas reserver une quantité superieur au stock disponible.");
        }
        $haveIReserved = $this->HaveIReserved($user, $product);
        if($haveIReserved === false){
            $response = $this->NewReserve($user, $product, $quantity);
        }
        else{
            $response = $this->ModifyReserve($user, $product, $quantity);
        }
        $MAJQuantityProduct = $this-> MAJQuantityProduct($product, $quantity);
        return true;
    }

    private function MAJQuantityProduct(int $product, int $adQuantity): bool{
        $bdd = parent::Connection();
        $req = $bdd->prepare('UPDATE product SET quantity = quantity - :adQuantity WHERE id = :id');
        $req ->execute(array('id' => $product, 'adQuantity' => $adQuantity ));
        return true;
    }

    private function HaveIReserved(int $user, int $product): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT id FROM reserved_product WHERE user = :user and product = :product');
        $req ->execute(array('user' => $user, 'product' => $product));
        $response = $req->fetch();
        if($response == false){
            return false;
        }
        else{
            return true;
        }
    }

    private function NewReserve(int $user, int $product, int $quantity): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('INSERT INTO reserved_product (user, product, quantity) VALUE (:user, :product, :quantity)');
        $req ->execute(array('user' => $user, 'product' => $product, 'quantity' => $quantity));
        return true;
    }

    private function ModifyReserve(int $user, int $product, int $adQuantity): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('UPDATE reserved_product SET quantity = quantity + :adQuantity WHERE user = :user AND product = :product');
        $req ->execute(array('user' => $user,'product' => $product,'adQuantity' => $adQuantity));
        return true;
    }

    public function DeleteReservation(int $product, int $id): bool {
        $bdd = parent::Connection();

        $req = $bdd->prepare('SELECT * FROM reserved_product WHERE id= :id');
        $req ->execute(array( 'id' => $product ));
        $reserved = $req->fetch();
        if($reserved === false){
            throw new Exception("Vous n'avez pas reservé de produits.");
        }
        elseif($reserved['user'] != $id){
            throw new Exception("Vous n'avez pas fait de reservation pour ce produit.");            
        }
        else{
            $req = $bdd->prepare('DELETE FROM reserved_product WHERE id = :id');
            $req ->execute(array('id' => $product));

            $req = $bdd->prepare('UPDATE product SET quantity = quantity + :addquantity WHERE id = :id');
            $req ->execute(array( 'addquantity' => $reserved['quantity'], 'id' => $reserved['product'] ));
            return true;
        }
    }

}