<?php

require_once ('view/backend/connectDB.php');

$errorsCreate = array();
$title = htmlspecialchars($_POST['title']);
$description = htmlspecialchars($_POST['description']);

if(empty($_FILES['picture']['size'])){
    $errorsCreate['picture'] = "vous devez mettre une photo du produit.";
}
elseif(!$_FILES['picture']['type'] == 'image/png' || !$_FILES['picture']['type'] == 'image/jpg' || !$_FILES['picture']['type'] == 'image/jpeg'){
    $errorsCreate['picture'] = "votre photo n'est pas au bon format(JPG, JPEG, PNG).";
}

if(empty($title)){
    $errorsCreate['title'] = "Le produit ne possède pas de nom.";
}elseif(!preg_match('/^[^\\|*#\/@<>\[\]{}€$£¤§\t\n\r]+$/', $title)){
    $errorsCreate['title'] = "Le titre possède un ou plusieurs caractères interdits ( / | \\ * # @ [] <> {} € \$ ¤ £ § ).";
}

if(empty($description)){
    $errorsCreate['description'] = "Le produit ne possède pas de description.";
}elseif(!preg_match('/^[^|<>\[\]{}¤§\t\r]+$/', $description)){
    $errorsCreate['title'] = "la description possède un ou plusieurs caractères interdits (  | [] <> {} ¤ § ).";
}

if(empty($_POST['type'])){
    $errorsCreate['type'] = "Vous n'avez pas désigné un type pour le produit.";
}
elseif(is_int($_POST['type'])){
    $errorsCreate['type'] = "Ce type de produit n'existe pas.";
}
else{
    $req = $bdd->prepare('SELECT id FROM product_type WHERE id =:id');
    $req ->execute(array(
        'id' => $_POST['type']
    ));
    if (empty($response = $req->fetch())){
        $errorsCreate['type'] = "Ce type de produit n'existe pas.";
    }
}

if(empty($_POST['quantity'])){
    $errorsCreate['type'] = "Vous devez renseigner la quantité du produit disponible.";
}
elseif(!ctype_digit($_POST['quantity'])){
    $errorsCreate['type'] = "Vous devez renseigner une quantité valide.";
}

if(empty($_POST['price'])){
    $errorsCreate['price'] = "Vous devez renseigner un prix.";
}
elseif(!is_numeric($_POST['price'])){
    $errorsCreate['price'] = "vous devez renseigner un prix valide.";
}
elseif($_POST['price'] < 0){
    $errorsCreate['price'] = "vous devez renseigner un prix valide.";
}

if(empty($errorsCreate)){
    header ("Content-type: image/jpg");

    if($_FILES['picture']['type'] == 'image/png'){
        $asource = imagecreatefrompng($_FILES['picture']['tmp_name']);
    }
    if($_FILES['picture']['type'] == 'image/jpg' || $_FILES['picture']['type'] == 'image/jpeg'){
        $asource = imagecreatefromjpeg($_FILES['picture']['tmp_name']);
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
    
    $name_file = 'pictureProduct/' . $title . date("YmdHis").'.jpeg';
    imagejpeg($imageFinal, $name_file, 100);

    $req = $bdd->prepare('INSERT INTO product(name, type, description, picture, quantity, price) VALUES(:name, :type, :description, :picture, :quantity, :price)');

    $req->execute(array(
        'name' => $title,
        'type' => $_POST['type'],
        'description' => $description,
        'picture' => $name_file,
        'quantity' => $_POST['quantity'],
        'price' =>  $_POST['price']
    ));

    header('location:index.php?win=addproduct');
}
?>