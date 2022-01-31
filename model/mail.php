<?php
include_once('model/interfaces.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('model/phpmailer/Exception.php');
require_once('model/phpmailer/PHPMailer.php');
require_once('model/phpmailer/SMTP.php');

final class SendMail implements SendMailInterface {

    public function MailToNewUser(int $id, string $token, string $mailUser){
        $mail = $this->SendMailToNewUser($id, $token, $mailUser);
        $alert = 'nouvel utilisateur : ' . $id;
        $mail = $this->SendMailToAlert($alert);
    }

    private function SendMailToAlert(string $message){
        $MailAlert = 'rackhamledev@gmail.com';
        $mail = $this->NewMail($mailAlert);
        $mail->Subject = "Musical Monk - Alerte Notification";
        
        $mail->Body = '
        <html>
            <head>
                <style type="text/css">
                    body {margin: 0; padding: 0; width: 100%!important;}
                    h1 {text-align: center; padding: 20px 0; border: 3px;}
                    div {justify-content: center; margin: 10px 0; text-align: center; }
                    a {font-size: 25px; margin: 20px; display: block;}
                    p {margin: 0;}
                </style>
            </head>

            <body>
                <h1>Notification</h1>
                <div>
                    <p>Une activé à été détécté sur le site Musical Monk.</p>
                    <p>' . $message . '</p>
                    <a href="https://admin.alwaysdata.com/"> gestion sur ALWAYSDATA </a>
                </div>

            </body>
        </html>';

        $mail->AltBody = 'Une activé à été détécté sur le site Musical Monk.' . $message ;
        $mail->send();
    }

    private function SendMailToNewUser( int $id, string $token, string $mailUser) {
        $mail = $this->NewMail($mailUser);
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
                </style>
            </head>

            <body>
                <h1>Bienvenue chez Musical-Monk !</h1>
                <div>
                    <p>vous venez de creer un compte utilisateur chez Musical-Monk.</p>
                    <p>Pour valider votre compte, merci de cliquer sur le lien ci-dessous.</p>
                    <a href="https://musical-monk.alwaysdata.net/index.php?page=mailconfirm&id=' . $id . '&token=' . $token . '" > je confirme mon adresse mail </a>
                </div>

            </body>
        </html>';

        $mail->AltBody = 'vous venez de creer un compte utilisateur chez Musical-Monk. Pour valider votre compte, merci de cliquer sur le lien suivant : http://localhost/Musical-Monk/index.php?page=mailconfirm&id=' . $id . '&token=' . $token ;
        $mail->send();
    }

    private function NewMail(string $mailUser) {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "***";
        $mail->SMTPAuth = true;
        $mail->Username = '***';
        $mail->Password = '***';
        $mail->CharSet = "utf-8";
        $mail->addAddress($mailUser);
        $mail->setFrom("no-reply@MusicalMonk.fr");
        $mail->isHTML(true);
        return $mail;
    }
}