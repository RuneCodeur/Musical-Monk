<?php 
//à faire
$title = 'Musical-Monk';

include_once("model/session.php");
include_once("model/user.php");
include_once("model/product.php");
include_once("model/admin.php");

try{
    try{
        $testAdmin = new User();
        $testAdmin = $testAdmin->TestAdmin($_SESSION['auth']['id']);
        if($testAdmin !== true){
            session::SessionDestroy();
            header('location: index.php?err=connexion');
            die();
        }
        if(!empty($_POST)){
            $createProduct = new Admin();
            $createProduct = $createProduct -> CreateProduct($_POST, $_FILES);
            if($createProduct == true){
                echo '<div class="win"> Nouveau produit dans la base de donnée !</div>' ;
            }
        }
        
    $product = new Product;
    $listTypes = $product -> AllTypeProduct();
    }
    catch(Exception $e){
        echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
    }
    
    //view
    include_once('view/admin/add-product.php');
}
catch(Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');