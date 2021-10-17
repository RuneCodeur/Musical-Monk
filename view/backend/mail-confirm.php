<?php
require_once ('view/backend/connectDB.php');

$id = $_GET['id'];
$token = $_GET['token'];

$req = $bdd->prepare('SELECT * FROM users WHERE id =:id');
$req->execute(array(
    'id' => $id
));

$response = $req->fetch();

//test si le token est valide
if($response['token_validation'] == $token){
    $req = $bdd->prepare('UPDATE users SET token_validation = NULL, date_validation = NOW() WHERE id =:id');
    $req->execute(array(
        'id' => $id
    ));
    
    require_once ('view/backend/sessionStart.php');
    connect_user($response);

    header('location: index.php?page=account&win=validtoken');
    exit();

}else{
    header('location: index.php?page=login&error=invalidtoken');
    exit();
}
