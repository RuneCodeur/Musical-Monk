<?php

if(isset($_SESSION['expire'])){
    if(time() < $_SESSION['expire']){
        $_SESSION['expire'] = time() + 10;
    }
    else
    {
        session_unset();
        session_destroy();
        header('location:index.php');
    }
}