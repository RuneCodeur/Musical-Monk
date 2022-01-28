<?php
//à faire
$title = 'Musical-Monk';

include_once('model/product.php');

try{
    try{
        if(!isset($_GET['id'])){
            header('location:index.php');
            die();
        }
        elseif(isset($_POST['quantity'])){
            $reservation = new Product();
            $reservation = $reservation -> Reservation($_SESSION['auth']['id'], $_GET['id'], $_POST['quantity']);
            if($reservation === true){
                echo '<div class="win"> votre reservation à bien été effectuée! </div>';
            }
        }
    }
    catch(Exception $e){
        echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
    }
    $product = new Product();
    $product = $product ->OneProduct($_GET['id']);

    //view
    include_once('view/user/product.php');

}
catch(Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');