<?php

$errorsCreate = array();
require_once ('view/backend/connectDB.php');

if(empty($_POST['create-pseudo']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['create-pseudo'])){
    $errorsCreate['pseudo'] = 'votre pseudo est invalide.';
}else{
    $req = $bdd->prepare('SELECT id FROM users WHERE pseudo = :pseudo');
    $req ->execute(array(
        'pseudo' => $_POST['create-pseudo']
    ));
    $user = $req->fetch();
    if($user){
        $errorsCreate['pseudo'] = 'pseudo déja utilisé.';
    }
}

if(empty($_POST['create-mail']) || !filter_var($_POST['create-mail'], FILTER_VALIDATE_EMAIL)){
    $errorsCreate['mail'] = 'votre email est invalide.';
}else{
    $req = $bdd->prepare('SELECT id FROM users WHERE mail = :mail');
    $req ->execute(array(
        'mail' => $_POST['create-mail']
    ));
    $mail = $req->fetch();
    if($mail){
        $errorsCreate['mail'] = 'ce mail est déja utilisé.';
    }
}

if(empty($_POST['create-mdp']) || $_POST['create-mdp'] != $_POST['confirm-mdp']){
    $errorsCreate['password'] = 'le mot de passe n\'est pas valide';
}

if(empty($errorsCreate)){
    $req = $bdd->prepare('INSERT INTO users(pseudo, mail, mdp) VALUES(:pseudo, :mail, :mdp)');

    $req->execute(array(
        'pseudo' => $_POST['create-pseudo'],
        'mail' => $_POST['create-mail'],
        'mdp' => password_hash($_POST['create-mdp'], PASSWORD_BCRYPT) 
    ));
    echo 'c\'est bon sa marche';
}