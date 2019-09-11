<?php

$safe = array_map('strip_tags', $_POST);

/* import des classes de PHPMailer */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Load Composer's autoloader
require '../vendor/autoload.php';

$mail = new PHPMailer;
/* paramétrage du SMTP */

$success = '';
$mail->SMTPOptions = ['ssl' => 
                    ['verify_peer' => false,
                     'verify_peer_name' => false,
                     'allow_self_signed' => true]
                 ];
// $mail->SMTPDebug = 3; //mode debug si > 2
$mail->CharSet = 'UTF-8'; //charset utf-8
$mail->isSMTP(); //connexion directe à un serveur SMTP
$mail->isHTML(true); //mail au format HTML
$mail->Host = 'smtp.gmail.com'; //serveur SMTP
$mail->SMTPAuth = true; //serveur sécurisé
$mail->Port = 465; //port utilisé par le serveur
$mail->SMTPSecure = 'ssl'; //certificat SSL
$mail->Username = 'wf3toulouse@gmail.com'; //login 
$mail->Password = '244Seysses'; //mot de passe
$mail->AddAddress('stephh31000@gmail.com'); //destinataire
$mail->SetFrom('wf3toulouse@gmail.com', 'Ookan'); //expediteur
$mail->Subject = 'Message de '.$safe['email']; //sujet
// le corps du mail au forma HTML
$mail->Body = '<html>
                                <head>
                                 <style>
                                  h1{color: green; }
                                 </style>
                                </head>
                                <body>
                                 <p>Adresse mail: '.$safe['email'].'</p>
                                 <p>Type de demande: '.$safe['demande'].'</p>
                                 <p>Dites-nous en plus: '.$safe['message'].'</p>
                                </body>
                             </html>';
$success = 'Votre demande a bien été envoyé, Ookan vous remercie !';
// envoi
if ($mail->Send()) {
        echo '<script>
                alert("Votre mail à été envoyé. Merci de nous avoir contacté")
                window . location . href = "http://127.0.0.1:8000/contactez-nous";
                </script>';
    } else echo '< script > alert("Une erreur est survenue, veuillez réessayer plus tard")
            window . location . href = "http://127.0.0.1:8000/contactez-nous";
            </script>';
?>
