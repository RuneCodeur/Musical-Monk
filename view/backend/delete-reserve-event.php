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
if(isset($_GET['deleteevent'])){
    if(preg_match('/^[0-9]+$/', $_GET['deleteevent'])){
        $req = $bdd->prepare('SELECT * FROM reserved WHERE id= :id ');
        $req ->execute(array(
            'id' => $_GET['deleteevent']
        ));
        $reserved = $req->fetch();
        if(!empty($reserved)){
            if($reserved['user'] != $_SESSION['auth']['id']){
                header('location: ../../index.php?err=baduser');
                die();
            }
            else{
                if($reserved['friend'] == 1){
                    $remove_user = 2 ;
                }else{
                    $remove_user = 1 ;
                }
            }
        }else{
            header('location: ../../index.php?err=notevent');
            die();
        }
    }else{
        header('location: ../../index.php?err=notevent');
        die();
    }
}else{
    header('location: ../../index.php?err=notevent');
    die();
}




$req = $bdd->prepare('DELETE FROM reserved WHERE id = :id');
$req ->execute(array(
    'id' => $_GET['deleteevent']
));

$req = $bdd->prepare('UPDATE events SET registration = registration - :removeuser WHERE id = :id');
$req ->execute(array(
    'removeuser' => $remove_user,
    'id' => $reserved['event']
));

header('location: ../../index.php?page=account&delete=event');
die();