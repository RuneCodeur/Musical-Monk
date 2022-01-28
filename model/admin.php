<?php
include_once('model/connectDB.php');

final class Admin extends ConnectDB {

    public function CreateProduct (array $product, array $picture) : bool{
        $testTitle = $this->TestTitle($product['title']);
        $testDescription = $this->TestDescription($product['description']);
        $testType = $this->TestType($product['type']);
        $testPrice = $this->TestPrice($product['price']);
        $testQuantity = $this->TestQuantity($product['quantity']);
        $TestPicture = $this->TestPicture($picture);
        if($testTitle != true){
            throw new Exception("Le titre possède un ou plusieurs caractères interdits ( | * # @ [] <> {} € \$ ¤ £ § ).");
        }
        if($testDescription != true){
            throw new Exception("La description possède un ou plusieurs caractères interdits ( | [] <> {} ¤ § ).");
        }
        if($testType != true){
            throw new Exception("Le type du produit n'est pas conforme.");
        }
        if($testPrice != true){
            throw new Exception("On ne peux pas vendre un produit à ce prix là.");
        }
        if($testQuantity != true){
            throw new Exception("Vous devez renseigner une quantité valide.");
        }
        if($TestPicture != true){
            throw new Exception("Vous devez mettre une image valide.");
        }

        $registrePicture = $this->RegistrePicture($picture, $product['title']);
        $registreIntoBDD = $this->RegistreIntoBDD($product, $registrePicture);
        return true;
    }

    private function RegistreIntoBDD(array $product, string $picturePicture): bool {
        $bdd = parent::Connection();
        $title = htmlspecialchars($product['title']);
        $description = str_replace(array("\n", "\r"), '<br />', htmlspecialchars($product['description']));
        $req = $bdd->prepare('INSERT INTO product(name, type, description, picture, quantity, price) VALUES(:name, :type, :description, :picture, :quantity, :price)');
        $req->execute(array('name' => $title, 'type' => $product['type'], 'description' => $description, 'picture' => $picturePicture, 'quantity' => $product['quantity'], 'price' =>  $product['price']));
        return true;
    }

    private function RegistrePicture(array $picture, string $title): string {
        if($picture['picture']['type'] == 'image/png'){
            $asource = imagecreatefrompng($picture['picture']['tmp_name']);
        }
        if($picture['picture']['type'] == 'image/jpg' || $picture['picture']['type'] == 'image/jpeg'){
            $asource = imagecreatefromjpeg($picture['picture']['tmp_name']);
        }

        $largeur_max = 500;
        $hauteur_max = 500;
        $largeur_source = imagesx($asource);
        $hauteur_source = imagesy($asource);
        $ratio = $largeur_source / $hauteur_source;
        if($largeur_max/$hauteur_max > $ratio){
            $largeur_max = $hauteur_max * $ratio;
        }
        else{
            $hauteur_max = $largeur_max/$ratio;
        }

        $imageFinal = imagecreatetruecolor($largeur_max, $hauteur_max) ;
        $final = imagecopyresampled($imageFinal, $asource, 0,0,0,0, $largeur_max, $hauteur_max, $largeur_source, $hauteur_source) ;
        $nameFile = 'pictureProduct/' . str_replace(' ', '', htmlspecialchars($title)) . date("YmdHis").'.jpeg';
        imagejpeg($imageFinal, $nameFile, 100);
        return $nameFile;
    }
    
    private function TestTitle(string $title): bool {
        if(empty($title)){
            throw new Exception("Le produit ne possède pas de titre.");
        }
        elseif(!preg_match('/^[^|*#@<>\[\]{}€$£¤§\t\n\r]+$/', $title)){
            return false;
        }
        else{
            return true;
        }
    }

    private function TestDescription(string $description): bool {
        if(empty($description)){
            throw new Exception("Le produit ne possède pas de description.");
        }
        elseif(!preg_match('/^[^|<>\[\]{}]+$/', $description)){
            return false;
        }
        else{
            return true;
        }
    }

    private function TestType(int $type): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT id FROM product_type WHERE id =:id');
        $req ->execute(array('id' => $type));
        $response = $req-> fetch();
        if($response === false){
            return false;
        }
        else{
            return true;
        }
    }
    private function TestPrice(float $price): bool {
        if($price < 0){
            return false;
        }
        else{
            return true;
        }
    }

    private function TestQuantity(int $quantity): bool {
        if($quantity < 0){
            return false;
        }
        else{
            return true;
        }
    }

    private function TestPicture(array $picture): bool {
        if(empty($picture['picture']['size'])){
            return false;
        }
        elseif(!$picture['picture']['type'] == 'image/png' || !$picture['picture']['type'] == 'image/jpg' || !$picture['picture']['type'] == 'image/jpeg'){
            throw new Exception("votre photo n'est pas au bon format(JPG, JPEG, PNG).");
        }
        else{
            return true;
        }
    }
}