<?php

require_once ('connectDB.php');

$errorsCreate = array();

//test si l'utilisateur est valide 
if(!isset($_SESSION['auth'])){
    session_destroy();
    header('location: index.php?err=connexion');
    die();
}

elseif(isset($_SESSION['auth']['id']) AND isset($_SESSION['auth']['pseudo'])){
    require_once ('view/backend/connectDB.php');
    $req = $bdd->prepare('SELECT admin FROM users WHERE pseudo = :pseudo AND id = :id');
    $req ->execute(array(
        'pseudo' => $_SESSION['auth']['pseudo'],
        'id' => $_SESSION['auth']['id']
    ));
    $user = $req->fetch();
    if($user['admin'] != 1){
        session_unset();
        session_destroy();
        header('location: index.php?err=unauthorized');
        die();
    }
}else{
    session_destroy();
    header('location: index.php?err=connexion');
    die();
}

//test si la photo du produit est valide
if(empty($_FILES['picture']['size'])){
    $errorsCreate['picture'] = "vous devez mettre une photo du produit.";
}
elseif(!$_FILES['picture']['type'] == 'image/png' || !$_FILES['picture']['type'] == 'image/jpg' || !$_FILES['picture']['type'] == 'image/jpeg'){
    $errorsCreate['picture'] = "votre photo n'est pas au bon format(JPG, JPEG, PNG).";
}

//test si le titre est valide
if(empty($_POST['title'])){
    $errorsCreate['title'] = "Le produit ne possède pas de nom.";
}elseif(!preg_match('/^[^\\|*#\/@<>\[\]{}€$£¤§\t\n\r]+$/', $_POST['title'])){
    $errorsCreate['title'] = "Le titre possède un ou plusieurs caractères interdits ( / | \\ * # @ [] <> {} € \$ ¤ £ § ).";
}else{   
    $title = htmlspecialchars($_POST['title']);
}

//test si la description est valide
if(empty($_POST['description'])){
    $errorsCreate['description'] = "Le produit ne possède pas de description.";
}elseif(!preg_match('/^[^|<>\[\]{}¤§\t\r]+$/', $_POST['description'])){
    $errorsCreate['title'] = "la description possède un ou plusieurs caractères interdits (  | [] <> {} ¤ § ).";
}else{   
    $description = htmlspecialchars($_POST['description']);
}

//test si le type est valide
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

//test si la quantité est valide
if(empty($_POST['quantity'])){
    $errorsCreate['type'] = "Vous devez renseigner la quantité du produit disponible.";
}
elseif(!ctype_digit($_POST['quantity'])){
    $errorsCreate['type'] = "Vous devez renseigner une quantité valide.";
}

//test si le produit est valide
if(empty($_POST['price'])){
    $errorsCreate['price'] = "Vous devez renseigner un prix.";
}
elseif(!is_numeric($_POST['price'])){
    $errorsCreate['price'] = "vous devez renseigner un prix valide.";
}
elseif($_POST['price'] < 0){
    $errorsCreate['price'] = "vous devez renseigner un prix valide.";
}


//si toutes les valeurs sont ok
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