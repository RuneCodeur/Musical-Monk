<?php

trait TestAware{

    public function TestTitle(string $title): bool {
        if(empty($title)){
            throw new Exception("Pas de titre.");
        }
        elseif(!preg_match('/^[^|*#@<>\[\]{}€$£¤§\t\n\r]+$/', $title)){
            return false;
        }
        else{
            return true;
        }
    }

    public function TestDescription(string $description): bool {
        if(empty($description)){
            throw new Exception("Pas de description.");
        }
        elseif(!preg_match('/^[^|<>\[\]{}]+$/', $description)){
            return false;
        }
        else{
            return true;
        }
    }
    
    public function TestType(int $type): bool {
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

    public function TestPrice(float $price): bool {
        if($price < 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function TestPostiveNumber(int $quantity): bool {
        if($quantity < 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function TestPicture(array $picture): bool {
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