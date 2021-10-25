<?php

require_once ('PHP/function.php');
require_once ('view/backend/connectDB.php');
$errorsCreate = array();

//test si le pseudo est valide
if(empty($_POST['create-pseudo']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['create-pseudo'])){
    $errorsCreate['pseudo'] = 'votre pseudo est invalide.';
}else{
    $req = $bdd->prepare('SELECT id FROM users WHERE pseudo = :pseudo');
    $req ->execute(array(
        'pseudo' => $_POST['create-pseudo']
    ));
    $result = $req->fetch();
    if($result){
        $errorsCreate['pseudo'] = 'pseudo déja utilisé.';
    }
}

//test si le mail est valide
if(empty($_POST['create-mail']) || !filter_var($_POST['create-mail'], FILTER_VALIDATE_EMAIL)){
    $errorsCreate['mail'] = 'votre email est invalide.';
}else{
    $req = $bdd->prepare('SELECT id FROM users WHERE mail = :mail');
    $req ->execute(array(
        'mail' => $_POST['create-mail']
    ));
    $result = $req->fetch();
    if($result){
        $errorsCreate['mail'] = 'ce mail est déja utilisé.';
    }
}

//test si le mdp est valide
if(empty($_POST['create-mdp']) || $_POST['create-mdp'] != $_POST['confirm-mdp']){
    $errorsCreate['password'] = 'le mot de passe n\'est pas valide';
}

//si toutes les valeurs sont ok
if(empty($errorsCreate)){

    $token = str_random(60);

    $req = $bdd->prepare('INSERT INTO users(pseudo, mail, mdp, token_validation) VALUES(:pseudo, :mail, :mdp, :token)');
    $req->execute(array(
        'pseudo' => $_POST['create-pseudo'],
        'mail' => $_POST['create-mail'],
        'mdp' => password_hash($_POST['create-mdp'], PASSWORD_BCRYPT),
        'token' => $token
    ));
    $userID = $bdd->lastInsertId();

    require_once ('view/backend/send-mail.php');
    mailAccount($_POST['create-mail'], $token, $userID);
    
    header('location: index.php?page=confirmcreateaccount');
}