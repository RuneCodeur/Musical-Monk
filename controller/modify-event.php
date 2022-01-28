<?php //doit rajouter de la secu
$title = 'Musical-Monk';

include_once("model/session.php");
include_once("model/event.php");

try{
    if(!isset($_SESSION['auth']['id'])){
        Session::SessionDestroy();
        header('location: index.php?page=planning&err=connexion');
        die();
    }
    elseif(!empty($_POST)){
        $modifyEvent = new Event();
        $modifyEvent = $modifyEvent -> ModifyEvent($_GET['id'], $_POST);
        if($modifyEvent === true){
            header('location: index.php?page=event&id=' . $_GET['id']);
            die();
        }
    }
    else{
        $infoEvent = new Event();
        $infoEvent = $infoEvent -> OneEvent($_GET['id']);
        $date = explode(' ', $infoEvent['date']);
    }
    
    //view
    include_once('view/user/modify-event.php');
}
catch(Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');