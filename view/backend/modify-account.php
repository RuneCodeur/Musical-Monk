<?php

require_once ('view/backend/connectDB.php');
$errorsCreate = array();
$validationCreate = array();

//test si l'utilisateur est bien lui même
if(!isset($_SESSION['auth']['id'])){
    session_destroy();
    header('location: index.php?err=connexion');
    die;
}
else{
    $req = $bdd->prepare('SELECT pseudo FROM users WHERE id= :id ');
    $req ->execute(array(
        'id' => $_SESSION['auth']['id']
    ));
    $res = $req->fetch();
    if(empty($res) || $res['pseudo'] != $_SESSION['auth']['pseudo']){
        session_unset();
        session_destroy();
        header('location: index.php?err=baduser');
        die;
    }
}

//test si le pseudo est valide
if(isset($_GET['modify']) && $_GET['modify'] == 'pseudo'){ 
    if(empty($_POST['modify-pseudo']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['modify-pseudo'])){
        $errorsCreate['pseudo'] = 'votre pseudo est invalide.';
    }else{
        $req = $bdd->prepare('SELECT id FROM users WHERE pseudo = :pseudo');
        $req ->execute(array(
            'pseudo' => $_POST['modify-pseudo']
        ));
        $res = $req->fetch();
        if($res){
            if($res == $_SESSION['auth']['id']){
                $errorsCreate['pseudo'] = 'Vous utilisez déjà ce pseudo.';
            }else{
                $errorsCreate['pseudo'] = 'ce pseudo est déja utilisé par un autre utilisateur.';
            }
        }
        else{
            //pseudo conforme, applique les modifs
            $req = $bdd->prepare('UPDATE users SET pseudo = :pseudo WHERE id = :id');
            $req->execute(array(
                'pseudo' =>  $_POST['modify-pseudo'],
                'id' => $_SESSION['auth']['id']
            ));
            $_SESSION['auth']['pseudo'] = $_POST['modify-pseudo'];
            header('location: index.php?modified=pseudo');
            die;
        }
    }
}

//test si le mail est valide
elseif(isset($_GET['modify']) && $_GET['modify'] == 'mail'){
    echo 'mail';
    if(!filter_var($_POST['modify-mail'], FILTER_VALIDATE_EMAIL)){
        $errorsCreate['mail'] = 'votre email est invalide.';
    }else{
        $req = $bdd->prepare('SELECT id FROM users WHERE mail = :mail');
        $req ->execute(array(
            'mail' => $_POST['modify-mail']
        ));
        $result = $req->fetch();
        if($result){
            if($result == $_SESSION['auth']['id']){
                $errorsCreate['mail'] = 'Vous utilisez déjà cette adresse mail.';
            }else{
                $errorsCreate['mail'] = 'Cette adresse mail est déjà utilisé par un autre utilisateur.';
            }
        }
        else{
            //mail conforme, applique les modifs
            $req = $bdd->prepare('UPDATE users SET mail = :mail WHERE id = :id');
            $req->execute(array(
                'mail' =>  $_POST['modify-mail'],
                'id' => $_SESSION['auth']['id']
            ));

            $_SESSION['auth']['mail'] = $_POST['modify-mail'];
            header('location: index.php?modified=mail');
            die;
        }
    }
}

//test si le mdp est valide
elseif(isset($_GET['modify']) && $_GET['modify'] == 'mdp'){
    if(empty($_POST['modify-mdp']) || empty($_POST['confirm-modify-mdp']) || ($_POST['modify-mdp'] != $_POST['confirm-modify-mdp'])){
        $errorsCreate['password'] = 'le mot de passe n\'est pas valide';
    }
    else{
        //mdp valide, applique les modifs
        $req = $bdd->prepare('UPDATE users SET mdp = :mdp WHERE id = :id');
        $req->execute(array(
            'mdp' =>  password_hash($_POST['modify-mdp'], PASSWORD_BCRYPT),
            'id' => $_SESSION['auth']['id']
        ));

        header('location: index.php?modified=mdp');
        die;
    }
}

else{
    header('location: index.php?err=connection');
    die;
}