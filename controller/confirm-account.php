<?php //fini
$title = 'Musical-Monk';

include_once('model/user.php');

try{
    $user = new User;
    $user = $user -> MailConfirm($_GET['id'], $_GET['token']);

    if( $user === true ){
        echo '<div class="win"> votre adresse mail à été validé </div>';
    }
    else{
        echo '<div class="err"> désolé, ce lien n\'est pas valide.</div>';
    }
}
catch(Exception $e){
    echo '<div class="err"> erreur : ' . $e->getMessage() . '</div>';
}

$content = ob_get_clean();

include_once('template.php');