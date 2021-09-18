<?php
function mailAccount($mail, $token, $ID){

    $message = '<html>
    <head>
    <title> validation de votre compte </title>
    </head>
    <body>
        
        <p>Pour valider votre compte, merci de cliquer sur ce lien</p>
        <a href="http://localhost/Musical-Monk/index.php?page=mailconfirm&id='. $ID . '&token='. $token .'" > je confirme mon adresse mail </a>

    </body>
    </html>';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: "Expéditeur" <email@expediteur.fr>' . "\r\n";

    mail($mail,'confirmation de création du compte - Musical Monk',$message, $headers);

}
