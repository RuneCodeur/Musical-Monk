<?php
class Session {

    public static function SessionStart($user) {
        $_SESSION['auth'] = $user;
        $_SESSION['expire'] = time() + 180;
    }

    public static function SessionLife() {
        if(isset($_SESSION['expire'])){
            if(time() < $_SESSION['expire']){
                $_SESSION['expire'] = time() + 60*60*2;
            }
            else
            {
                self::SessionDestroy();
            }
        }
    }
    
    public static function SessionDestroy() {
        session_unset();
        session_destroy();
        header('location: index.php');
    }

}