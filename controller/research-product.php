<?php
//à faire
$title = 'Musical-Monk';

include_once('model/product.php');

try{
    if(!isset($_GET['search']) && !isset($_GET['typesearch'])){
        header('location:index.php');
        die();
    }
    
    $product = new Product;

    $listTypes = $product -> AllTypeProduct();
    $listProduct = $product -> SearchProduct( $_GET['typesearch'], $_GET['search'] );

    //view
    include_once('view/user/research-product.php');

}
catch(Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');