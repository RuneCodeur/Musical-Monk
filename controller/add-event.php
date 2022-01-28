<?php //rajouter de la secu
$title = 'Musical-Monk';

include_once("model/session.php");
include_once("model/event.php");

try{
    try{
        if(!isset($_SESSION['auth']['id'])){
            Session::SessionDestroy();
            header('location: index.php?page=planning&err=connexion');
            die;
        }
        else if(!empty($_POST)){
            $createEvent = new Event();
            $createEvent = $createEvent -> CreateEvent($_SESSION['auth']['id'], $_POST);
            if($createEvent === true){
                header('location: index.php?page=planning&win=eventcreate');
            }
        }
    }
    catch(Exception $e){
        echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
    }
    
    //view
    include_once('view/user/add-event.php');
}
catch(Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');