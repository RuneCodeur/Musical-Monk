<?php
//rajouter la suppression des products
$title = 'Musical-Monk';

include_once("model/session.php");
include_once("model/user.php");
include_once("model/product.php");
include_once("model/event.php");

try{
    try{
        if(!isset($_SESSION['auth']['id'])){
            Session::SessionDestroy();
            header('location: index.php?page=planning&err=connexion');
            die();
        }
        elseif(isset($_GET['modify'])){
            $user = new User();
            $modifyUser = $user -> ModifyUser( $_SESSION['auth']['id'], $_POST);
            if($modifyUser === true){
                echo '<div class="win"> votre profil à bien été modifié! </div>';
            }
        }
        elseif(isset($_GET['deleteevent'])){
            $deleteEvent = new event();
            $deleteEvent = $deleteEvent -> DeleteReservation($_GET['deleteevent'], $_SESSION['auth']['id']);
        }
        elseif(isset($_GET['deleteproduct'])){
            $deleteProduct = new Product();
            $deleteProduct = $deleteProduct -> DeleteReservation($_GET['deleteproduct'], $_SESSION['auth']['id']);
        }
    }
    catch(Exception $e){
        echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
    }

    $product = new Product();
    $event = new event();
    $productReserved = $product -> ProductReserved($_SESSION['auth']['id']);
    $eventReserved = $event -> EventReserved($_SESSION['auth']['id']);
    $myEvents = $event -> MyEvents($_SESSION['auth']['id']);

    //view
    include_once('view/user/account.php');
}
catch(Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');