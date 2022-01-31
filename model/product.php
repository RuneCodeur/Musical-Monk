<?php
include_once('model/connectDB.php');
include_once('model/interfaces.php');

final class Product extends ConnectDB implements ProductInterface {

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
    
    public function OneProduct( int $id): ?array {
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

}