<?php

if(isset($_SESSION['expire'])){
    if(time() < $_SESSION['expire']){
        $_SESSION['expire'] = time() + 180;
    }
    else
    {
        require_once ('view/backend/sessionDestroy.php');
    }
}
