<?php
session_start();
include("view/backend/sessionLife.php");

if(isset($_GET['search'])){
    include("view/frontend/research.php");
}
elseif(isset($_GET['page'])){
    $page= $_GET['page'];
            
    switch ($page) 						
    {
        
        case 'mailconfirm':
            include("view/backend/mailconfirm.php");
        break;

        case 'confirmcreateaccount':
            include("view/frontend/confirmcreateaccount.php");
        break;

        case 'resultevent':
            include("view/backend/addevent.php");
        break;

        case 'planning':
            include("view/frontend/planning.php");
        break;
                
        case 'contact':
            include("view/frontend/contact.php");
        break;
                    
        case 'addproduct':
            include("view/frontend/addproduct.php");
        break;

        case 'modifyevent':
            include("view/frontend/modify-event.php");
        break;
                    
        case 'login':
            include("view/frontend/login.php");;
        break;
                
        case 'product':
            include("view/frontend/product.php");
        break;
                
        case 'addevent':
            include("view/frontend/addevent.php");
        break;
                
        case 'event':
            include("view/frontend/event.php");
        break;
                
        case 'account':
            include("view/frontend/account.php");
        break;
                
        default:
            include("view/frontend/home.php");
    }
}
else
{
    include("view/frontend/home.php");
}
