<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/* import des classes de PHPMailer */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends AbstractController
{
     /**
     * @Route("/contact", name="contact")
     */

    public function contact()
    {

		/* paramétrage du SMTP */
		$mail = new PHPMailer;

		$disp = '';

		$success = '';

		$errors = [];
		
		if (!empty($_POST)) {


			$safe = array_map('trim', array_map('strip_tags', $_POST));

			if (strlen($safe['name']) <= 3) {
				$errors[] = 'Votre nom doit contenir au moins 4 caractères';
			}

			if(!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {
				$errors[] = 'Votre adresse email n\'est pas valide';
			}

			if (strlen($safe['demande']) < 5) {
				$errors[] = 'Votre message doit contenir au moins 5 caractères';
			}

				

			if (count($errors) == 0) {
				
					$errors = array_filter($errors);

			//paramétrage PHP mailer
			
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
			$mail->Body = '	<html>
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

				// envoi email
				if ($mail->Send()) {
					$success = 'Votre demande est un succès, Ookan vous remercie !';
					$disp = 'd-none';
				}
				
			} // Fin de if (count($errors) == 0)
		} // Fin de if (!empty($_POST))
		return $this->render('contact.html.twig', [
			'success' 			=> $success ?? null,
			'not_success' 		=> $errors,
			'disp_none'         => $disp ?? null,
		]);
    }
}