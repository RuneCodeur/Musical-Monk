<?php

require_once ('PHP/function.php');
require_once ('view/backend/connectDB.php');
$errorsCreate = array();

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

    $token = str_random(60);

    $req = $bdd->prepare('INSERT INTO users(pseudo, mail, mdp, token_validation) VALUES(:pseudo, :mail, :mdp, :token)');

    $req->execute(array(
        'pseudo' => $_POST['create-pseudo'],
        'mail' => $_POST['create-mail'],
        'mdp' => password_hash($_POST['create-mdp'], PASSWORD_BCRYPT),
        'token' => $token
    ));
    $userID= $bdd->lastInsertId();

    require_once ('view/backend/sendMail.php');
    mailAccount($_POST['create-mail'], $token, $userID);
    
    echo '<a href="http://localhost:81/Musical-Monk/index.php?page=mailconfirm&id='. $userID . '&token='. $token . '"> lien de validation </a>';
    //header('location: index.php?page=confirmcreateaccount');
}