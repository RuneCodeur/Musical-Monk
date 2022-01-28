<?php
//penser à faire les try and catch
$title = 'Musical-Monk';

include_once('model/event.php');
include_once('model/product.php');

try{
    $event = new Event;
    $product = new Product;

    $listTypes = $product -> AllTypeProduct();
    $randomProduct = $product -> RandomProduct();
    $randomEvent = $event -> RandomEvent();

    //view
    include_once('view/user/home.php');
    
}
catch(Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');