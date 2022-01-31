<?php
include_once('model/connectDB.php');
include_once('model/test.php');
include_once('model/interfaces.php');

final class Admin extends ConnectDB implements AdminInterface {
    use TestAware;

    public function CreateProduct (array $product, array $picture): bool{
        $testTitle = $this->TestTitle($product['title']);
        $testDescription = $this->TestDescription($product['description']);
        $testType = $this->TestType($product['type']);
        $testPrice = $this->TestPrice($product['price']);
        $testQuantity = $this->TestPostiveNumber($product['quantity']);
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
    
}