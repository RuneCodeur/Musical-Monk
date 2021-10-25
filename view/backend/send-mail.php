<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('PHP/phpmailer/Exception.php');
require_once('PHP/phpmailer/PHPMailer.php');
require_once('PHP/phpmailer/SMTP.php');

function mailAccount($adressMail, $token, $ID){

    $mail = new PHPMailer(true);

    try{

        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

        $mail->isSMTP();
        $mail->Host = "***";
        $mail->SMTPAuth = true;
        $mail->Username = '***';
        $mail->Password = '***';
        $mail->CharSet = "utf-8";
        $mail->addAddress($adressMail);
        $mail->setFrom("no-reply@MusicalMonk.fr");
        $mail->isHTML(true);

        $mail->Subject = "Musical Monk - confirmez la création de votre compte";

        $mail->Body = '
        <html>
            <head>
                <style type="text/css">
                    body {margin: 0; padding: 0; width: 100%!important;}
                    h1 {text-align: center; padding: 20px 0; border: 3px;}
                    div {justify-content: center; margin: 10px 0; text-align: center; }
                    a {font-size: 25px; margin: 20px; display: block;}
                    p {margin: 0;}
                    .warn { background-color: rgb(245, 86, 86);}
                    .att { font-weight: bolder;}
                </style>
            </head>

            <body>
                <h1>Bienvenue chez Musical-Monk !</h1>
                <div>
                    <p>vous venez de creer un compte utilisateur chez Musical-Monk.</p>
                    <p>Pour valider votre compte, merci de cliquer sur le lien ci-dessous.</p>
                    <a href="https://musical-monk.alwaysdata.net/index.php?page=mailconfirm&id=' . $ID . '&token=' . $token . '" > je confirme mon adresse mail </a>
                </div>

                <div class="warn">
                    <p class="att"> ! ATTENTION !</p>
                    <P>Nous vous rappelons que Musical-Monk est un site Vitrine. La marque Musical-Monk ainsi que tout les produits, les évènements et les lieux présent sur le site sont purement Fictifs.</P>
                </div>
            </body>
        </html>';

        $mail->AltBody = 'vous venez de creer un compte utilisateur chez Musical-Monk. Pour valider votre compte, merci de cliquer sur le lien suivant : http://localhost/Musical-Monk/index.php?page=mailconfirm&id=' . $ID . '&token=' . $token ;

        $mail->send();
        echo "ok";
    }catch(exception $e){
        echo "message non envoyé :" . $e ;
    }
}
$message = 
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: "Expéditeur" <email@expediteur.fr>' . "\r\n";

    mail($mail,'Musical Monk - confirmez la création de votre compte',$message, $headers);