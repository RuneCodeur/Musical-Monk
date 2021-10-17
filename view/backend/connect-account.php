<?php

require_once ('view/backend/connectDB.php');
$errorsCreate = array();

//test si le pseudo et le mdp est envoyé 
if(empty($_POST['connect-pseudo'])){
    $errorsCreate['pseudo'] = 'Vous avez besoin d\'un pseudo pour vous connecter.';
}
elseif(empty($_POST['connect-mdp'])){
    $errorsCreate['mdp'] = 'Vous avez besoin d\'un mot de passe pour vous connecter.';
}

//va chercher l'utilisateur
if(empty($errorsCreate)){
    $req = $bdd->prepare('SELECT * FROM users WHERE pseudo = :pseudo');
    $req ->execute(array(
        'pseudo' => $_POST['connect-pseudo']
    ));

    $user = $req->fetch();

    //test si c'est bien le bon utilisateur
    if(!$user){
        $errorsCreate['pseudo'] = 'Cet utilisateur n\'existe pas.';
    }else{
        if(!password_verify($_POST['connect-mdp'], $user['mdp'])){
            $errorsCreate['mdp'] = 'Le mot de passe est incorrect.';
        }
        if($user['date_validation'] == NULL){
            $errorsCreate['mail'] = 'Vous n\'avez pas validé votre adresse mail';
        }
    }
}

//si l'utilisateur est bon, connecte et renvoie sur la page du compte
if(empty($errorsCreate)){

    require_once ('view/backend/session-start.php');
    connect_user($user);
    
    header('location: index.php?page=account');
}