<?php
session_start();
require_once ('connectDB.php');

//test la valeur friend
if(isset($_POST['friend'])){
    if($_POST['friend'] !== 'on'){
        header('location: ../../index.php?page=planning&err=alt');
        die();
    }else{
        $friend = 1;
    }
}else{
    $friend = 0;
}

//test si l'utilisateur est bien connecté
if(!isset($_SESSION['auth'])){
    header('location: ../../index.php?page=planning&err=connection');
    die();
}

//test si l'utilisateur est bien lui même
$req = $bdd->prepare('SELECT id, pseudo FROM users WHERE id= :id ');
$req ->execute(array(
    'id' => $_SESSION['auth']['id']
));
$response = $req->fetch();

if($response == null){
    session_unset();
    session_destroy();
    header('location: ../../index.php?page=planning&err=baduser');
    die();
}else{
    if($response['pseudo'] != $_SESSION['auth']['pseudo']){
        session_unset();
        session_destroy();
        header('location: ../../index.php?page=planning&err=baduser');
        die();
    }
}

//test si il y a la place pour s'enregistrer
if(isset($_GET['id'])){
    $req = $bdd->prepare('SELECT max_registration, registration FROM events WHERE id= :id ');
    $req ->execute(array(
        'id' => $_GET['id']
    ));
    $infoevent = $req->fetch();

    if($infoevent == null){
        header('location: ../../index.php?page=planning&err=badevent');
        die();
    }else{
        $freeplaces = $infoevent['max_registration'] - $infoevent['registration'];
        if(($freeplaces < 1)){
            header('location: ../../index.php?page=planning&err=noplace');
            die();
        }
        elseif(($friend == 1) AND ($freeplaces < 2)){
            header('location: ../../index.php?page=planning&err=noplace');
            die();
        }
    }
    //test si l'utilisateur s'est déja enregistré
    $req = $bdd->prepare('SELECT id FROM reserved WHERE user = :user AND event = :event');
    $req ->execute(array(
        'user' => $_SESSION['auth']['id'],
        'event' => $_GET['id']
    ));
    $registred = $req->fetch();
    if($registred != null){
        header('location: ../../index.php?page=planning&err=registred');
        die();
    }
}else{
    header('location: ../../index.php?page=planning&err=badevent');
    die();
}



//autorise l'enregistrement
$req = $bdd->prepare('INSERT INTO reserved (user, event, friend) VALUE (:user, :event, :friend)');
$req ->execute(array(
    'user' => $_SESSION['auth']['id'],
    'event' => $_GET['id'],
    'friend' => $friend
));

//met à jour la table 'events'
$newregistration = $infoevent['registration'] + 1 + $friend;
$req = $bdd->prepare('UPDATE events SET registration = :registration WHERE id = :id');
$req ->execute(array(
    'registration' => $newregistration,
    'id' => $_GET['id']
));

header('location: ../../index.php?page=planning&win=registration');
die();