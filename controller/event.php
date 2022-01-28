<?php // fini
$title = 'Musical-Monk';

include_once('model/event.php');
include_once('model/calcul.php');

try{
    if(!isset($_GET['id'])){
        header('location:index.php');
        die();
    }
    try{
        if(isset($_GET['reservation']) AND $_GET['reservation'] === 'true'){
            if(isset($_POST['friend'])){
                if($_POST['friend'] !== 'on'){
                    throw new Exception("c'est quoi pour vous, l'amitié ?");
                }else{
                    $friend = 1;
                }
            }else{
                $friend = 0;
            }
            $reservation = new Event();
            $reservation = $reservation -> Reservation($_SESSION['auth']['id'], $_GET['id'], $friend);
            if($reservation === true){
                echo '<div class="win"> Vous avez été correctement enregistré pour cet évènement! </div>';
            }
        }
    }
    catch(Exception $e){
        echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
    }

    $event = new Event();

    $infoEvent = $event -> OneEvent($_GET['id']);
    $reservation = $event -> AllReserved($_GET['id']);
    $date = Calcul::ExplodeDate($infoEvent['date']);
    $duration = Calcul::ExplodeDuration($infoEvent['duration']);
    
    //view
    include_once('view/user/event.php');
}
catch(Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');