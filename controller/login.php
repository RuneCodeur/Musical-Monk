<?php //fini
$title = 'Musical-Monk';

include_once('model/user.php');
include_once('model/calcul.php');
include_once('model/session.php');

if(isset($_SESSION['auth'])){
    header('location: index.php?page=account');
    die();
}

try{
    try{
        if(isset($_POST['create-pseudo'])){
            
            $createUser = new User;
            $createUser = $createUser->CreateUser($_POST['create-pseudo'], $_POST['create-mail'], $_POST['create-mdp'], $_POST['confirm-mdp']);
        }
        elseif(isset($_POST['connect-pseudo'])){
            $connectUser = new User;
            $connectUser = $connectUser->ConnectUser($_POST['connect-pseudo'], $_POST['connect-mdp']);
            Session::SessionStart($connectUser);
            header('location: index.php');
            die();
        }
        if(isset($createUser) AND $createUser === true){
            echo '<div class="win">Un mail de confirmation à été envoyé dans votre boite mail.</div>' ;
        }
    }
    catch(Exception $e){
        echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
    }

    //view
    include_once('view/user/login.php');
}
catch (Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');