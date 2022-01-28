<?php
ob_start();
session_start();
include_once("model/session.php");
try{
    Session::SessionLife();
}
catch(Exception $e){
    echo '<div class="err"> erreur : une erreur est survenue avec votre compte. </div>';
}

//appel le controlleur
if(isset($_GET['disconnect'])){
    Session::SessionDestroy();
    header('location: index.php');
    die();
}
if(isset($_GET['search'])){
    include_once("controller/research-product.php");
}
elseif(isset($_GET['page'])){
    $page= $_GET['page'];
            
    switch ($page){
        
        case 'mailconfirm':
            include_once("controller/confirm-account.php");
        break;

        case 'planning':
            include_once("controller/research-event.php");
        break;
                
        case 'contact':
            include_once("controller/contact.php");
        break;
                
        case 'event':
            include_once("controller/event.php");
        break;
                    
        case 'login':
            include_once("controller/login.php");;
        break;

        case 'addevent':
            include("controller/add-event.php");
        break;

        case 'modifyevent':
            include("controller/modify-event.php");
        break;
                 
        case 'account':
            include("controller/account.php");
        break;
                
        case 'product':
            include("controller/product.php");
        break;
                    
        case 'addproduct':
            include("controller/add-product.php");
        break;
                
        default:
            include_once("controller/home.php");
        break;
    }
}
else
{
    include_once("controller/home.php");
}
