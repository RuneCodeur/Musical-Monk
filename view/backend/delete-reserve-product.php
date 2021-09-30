<?php
session_start();
require_once ('connectDB.php');

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
    header('location: ../../index.php?err=disconnect');
    die();
}


//check si l'id envoyé est valide
if(isset($_GET['deleteproduct'])){
    if(preg_match('/^[0-9]+$/', $_GET['deleteproduct'])){
        $req = $bdd->prepare('SELECT * FROM reserved_product WHERE id= :id ');
        $req ->execute(array(
            'id' => $_GET['deleteproduct']
        ));
        $reserved = $req->fetch();
        if(!empty($reserved)){
            if($reserved['user'] != $_SESSION['auth']['id']){
                header('location: ../../index.php?err=baduser');
                die();
            }
        }else{
            header('location: ../../index.php?err=notproduct');
            die();
        }
    }else{
        header('location: ../../index.php?err=notproduct');
        die();
    }
}else{
    header('location: ../../index.php?err=notproduct');
    die();
}




$req = $bdd->prepare('DELETE FROM reserved_product WHERE id = :id');
$req ->execute(array(
    'id' => $_GET['deleteproduct']
));

$req = $bdd->prepare('UPDATE product SET quantity = quantity + :addquantity WHERE id = :id');
$req ->execute(array(
    'addquantity' => $reserved['quantity'],
    'id' => $reserved['product']
));

header('location: ../../index.php?page=account&delete=product');
die();