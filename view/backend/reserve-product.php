<?php
require_once ('connectDB.php');
$created = 0;
$errorsCreate = array();

//test si l'utilisateur est bien lui même
if(isset($_SESSION['auth'])){
    $req = $bdd->prepare('SELECT id, pseudo FROM users WHERE id= :id ');
    $req ->execute(array(
        'id' => $_SESSION['auth']['id']
    ));
    $user = $req->fetch();

    if($user == null){
        session_unset();
        session_destroy();
        header('location: ../../index.php?err=baduser');
        die();
    }else{
        if($user['pseudo'] != $_SESSION['auth']['pseudo']){
            session_unset();
            session_destroy();
            header('location: ../../index.php?err=baduser');
            die();
        }
    }
}else{
    $errorsCreate['user'] = "vous devez vous connecter avant de reserver un produit";
}

//verifie si l'utilisateur à déja réservé ce produit
if(!isset($_POST['quantity'])){
    $errorsCreate['quantity'] = "vous devez renseigner une quantité de produit à enregistrer";
}else{
    $req = $bdd->prepare('SELECT quantity FROM reserved_product WHERE user = :user and product = :product');
    $req ->execute(array(
        'user' => $_SESSION['auth']['id'],
        'product' => $_GET['id'],
    ));
    $response = $req->fetch();
    if($response != null){
        $created = $response['quantity'];
    }
}

//test si le produit selectionnée existe bien
if(!isset($_GET['id'])){
    $errorsCreate['product'] = "vous n'avez pas selectionnée de produit à enregistrer.";
}else{
    $req = $bdd->prepare('SELECT quantity FROM product WHERE id = :id');
    $req ->execute(array(
        'id' => $_GET['id'],
    ));
    $product = $req->fetch();
    if($product == null){
        $errorsCreate['product'] = "ce produit n'existe plus.";
    }
}

//test si la quantité demandé est bien dispo
if(!preg_match('/^[0-9]+$/', $_POST['quantity'])){
    $errorsCreate['quantity'] = "vous ne pouvez pas utiliser des chiffres à virgules pour la quantité de produit à enregistrer";
}
if(($_POST['quantity']) < -1){
    $errorsCreate['quantity'] = "vous ne pouvez pas reserver une quantité négative de produit à enregistrer.";
}
$remaining_quantity = $product['quantity'] - $_POST['quantity'];
if($remaining_quantity < 0){
    $errorsCreate['quantity'] = "vous ne pouvez pas reserver une quantité superieur au stock disponible.";
}



//si tout est ok
if(empty($errorsCreate)){
    //crée une nouvelle ligne
    if($created == 0){
        $req = $bdd->prepare('INSERT INTO reserved_product (user, product, quantity) VALUE (:user, :product, :quantity)');
        $req ->execute(array(
            'user' => $_SESSION['auth']['id'],
            'product' => $_GET['id'],
            'quantity' => $_POST['quantity']
        ));
        echo 'ok';
    }
    //modifie la ligne déja présente
    elseif($created != 0){
        $req = $bdd->prepare('UPDATE reserved_product SET quantity = :quantity WHERE user = :user AND product = :product');
        $req ->execute(array(
            'user' => $_SESSION['auth']['id'],
            'product' => $_GET['id'],
            'quantity' => $_POST['quantity'] + $created
        ));
    }
    //modifie la table du produit
    $req = $bdd->prepare('UPDATE product SET quantity = :quantity WHERE id = :id');
    $req ->execute(array(
        'id' => $_GET['id'],
        'quantity' => $remaining_quantity
    ));
    header('location:index.php?win=registredproduct');
    die();
}