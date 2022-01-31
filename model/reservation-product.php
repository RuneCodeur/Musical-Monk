<?php
include_once('model/connectDB.php');
include_once('model/interfaces.php');

class ReservationProduct extends ConnectDB implements ReservationProductInterface{

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
            $response = $this->NewReservationProduct($user, $product, $quantity);
        }
        else{
            $response = $this->ModifyReservationProduct($user, $product, $quantity);
        }
        $MAJQuantityProduct = $this-> MAJQuantityProduct($product, $quantity);
        return true;
    }

    public function ListMyProductsReserved(int $user): ?array {
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

    public function DeleteMyProductReservation(int $product, int $id): bool {
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

    private function NewReservationProduct(int $user, int $product, int $quantity): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('INSERT INTO reserved_product (user, product, quantity) VALUE (:user, :product, :quantity)');
        $req ->execute(array('user' => $user, 'product' => $product, 'quantity' => $quantity));
        return true;
    }

    private function ModifyReservationProduct(int $user, int $product, int $adQuantity): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('UPDATE reserved_product SET quantity = quantity + :adQuantity WHERE user = :user AND product = :product');
        $req ->execute(array('user' => $user,'product' => $product,'adQuantity' => $adQuantity));
        return true;
    }

}