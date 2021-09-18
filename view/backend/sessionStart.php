<?php
function connect_user($user){
    $_SESSION['auth'] = $user;
    $_SESSION['expire'] = time() + 180;
}