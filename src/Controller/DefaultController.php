<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; //Connexion à la base données

use App\Entity\User; // Intéraction
use App\Entity\UserPro; // Intéraction
use App\Entity\PasswordForget; // Intéraction
use Symfony\Component\HttpFoundation\Session\Session;

/* import des classes de PHPMailer */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default/controler", name="default_controler")
     */
    public function index()
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function connect()
    {

        $errors = [];

        if (!empty($_POST)) {



            if (!empty($_POST['email']) || !empty($_POST['password'])) {

                $safe = array_map('trim', array_map('strip_tags', $_POST));

                if (!empty($safe['email'])) {

                    if(!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {

                        $errors[] = 'Votre adresse email n\'est pas valide';
                    }
                }   

            // Cherche avec l'email dans la table User.
                $userdata = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $safe['email']]);

            // Cherche avec l'email dans la table User Pro.
                $userdatapro = $this->getDoctrine()->getRepository(UserPro::class)->findOneBy(['email' => $safe['email']]);

            if ($userdata){//Si on trouve le mail dans la table User...

                if(!password_verify($safe["password"],$userdata->getPassword())){ //Check du mdp dans $safe et celui stocké dans la BDD
                    $errors[] = 'Erreur de mot de passe';
                }
            }

            else if ($userdatapro){//...Sinon on continu de chercher dans la table User Pro

                if(!password_verify($safe["password"],$userdatapro->getPassword())){
                    $errors[] = 'Erreur de mot de passe';
                }
            }

            else{// Si on ne trouve rien.
                $errors[] = 'Utilisateur introuvable';
            }

            if (count($errors) == 0) {
                /*
                $errors = array_filter($errors);

                if ($userdata){
                    $infos_user = [
                        'id_user'   => $userdata->getId(),
                        'pseudo'    => $userdata->getPseudo(),
                        'connected' => 'true',
                        'pro'       => 'non'
                    ];
                }
                else if ($userdatapro){
                      $infos_user = [
                        'id_user'   => $userdatapro->getId(),
                        'pseudo'    => $userdatapro->getPseudo(),
                        'connected' => 'true',
                        'pro'       => 'oui'
                    ];
                }


                $session = new Session();
                $session->set('user', $infos_user);

                return $this->redirectToRoute('user_profile', [
                'userinfo'     =>  $infos_user,    
                'user'         =>  $userdata,    
                'userpro'      =>  $userdatapro,    
                 ]);*/


                // $my_user_connected = $session->get('user');
                //  $my_user_connected['id_user'];
                //  $my_user_connected['email'];


                if(!empty($userdata)){
                    
                $session = new Session();
                $session->set('user_id',  $userdata->getId());
                $session->set('pseudo',  $userdata->getPseudo());
                $session->set('email',  $userdata->getEmail());
                $session->set('firstname',  $userdata->getFistname());
                $session->set('lastname',  $userdata->getName());
                $session->set('pro', 'non');
                $session->set('connected', 'true');

                return $this->redirectToRoute('user_profile');

                }  
                else if (!empty($userdatapro)){

                $session = new Session();
                $session->set('user_id',  $userdatapro->getId());
                $session->set('pseudo',  $userdatapro->getPseudo());
                $session->set('email',  $userdatapro->getEmail());
                $session->set('firstname',  $userdatapro->getFirstname());
                $session->set('lastname',  $userdatapro->getName());
                $session->set('siret',  $userdatapro->getSiret());
                $session->set('pro', 'oui');
                $session->set('connected', 'true');

                return $this->redirectToRoute('user_profile');


            }
        }   


        return $this->render('connexion.html.twig', [
            'mes_erreurs'     =>  $errors,    
        ]);
    }

}
return $this->render('connexion.html.twig', [
    'mes_erreurs'     =>  $errors,    
]);
}

public function disconnect(){

    session_destroy();

    return $this->redirectToRoute('accueil');
}

public function mentions()
{
    return $this->render('mentions.html.twig', [
    ]);
}
public function ookan_team()
{
    return $this->render('ookanteam.html.twig', [
    ]);
}

 public function PasswordForget()
    {
        $mail = new PHPMailer;

        $errors = [];

        $success = '';

        if (!empty($_POST['email']) && (!empty($_POST['email']))) {

            $safe = array_map('trim', array_map('strip_tags', $_POST));

            if (!empty($safe['email'])) {

                    if(!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {

                        $errors[] = 'Votre adresse email n\'est pas valide';
                    }
                }      
            

            if (count($errors) == 0) {

                $errors = array_filter($errors);

                $em = $this->getDoctrine()->getManager();

                $token = bin2hex(random_bytes(50));

                $bddToken = new PasswordForget();
                $bddToken->setToken($token)
                        ->setPseudo($safe['email']);
            

                $em->persist($bddToken);
                //éxecution
                $em->flush();

                $errors = array_filter($errors);

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
            $mail->AddAddress($safe['email']); //destinataire
            $mail->SetFrom('wf3toulouse@gmail.com', 'Ookan'); //expediteur
            $mail->Subject = 'Mot de passe perdu'; //sujet
            // le corps du mail au forma HTML
            $mail->Body = ' <html>
                                <head>
                                    <style>
                                        h1{color: green; }
                                    </style>
                                </head>
                                <body>
                                    <p>Pour réinitialiser votre mot de passe veuillez cliquer <a href="http://127.0.0.1:8000/new-password?token='.$token.'">ici</a></p>
                                </body>
                            </html>';

                // envoi email
                if ($mail->Send()) {
                    $success = 'Un email vous a été envoyer';
                }
            }
        }
return $this->render('password_forget.html.twig', [
            'mes_validation'    => $success,
            'mes_erreurs'       => $errors,
        ]);   
    }

    public function NewPassword()
        {
            $em = $this->getDoctrine()->getManager();

            $errors = [];

            $success = false;

            if (!empty($_POST)) {

                $safe = array_map('trim', array_map('strip_tags', $_POST));
                
                if (strlen($safe['password']) < 4) {
                $errors[] = 'Votre mot de passe doit contenir au moins 5 caractères';
                }
                elseif($safe['password'] != $safe['comfirm_password']) {
                $errors[] = 'Votre mot de passe n\'est pas identique';
                }

                  if (count($errors) == 0) {

                        $resultat = $this->getDoctrine()->getRepository(PasswordForget::class)->findOneBy(['token' => $_GET['token']]);

                        $userToChange = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $resultat->getPseudo()]);
                        $userProToChange = $this->getDoctrine()->getRepository(UserPro::class)->findOneBy(['email' => $resultat->getPseudo()]);
                        
                        if ($userToChange) {
                        
                        $userToChange->setPassword(password_hash($safe['password'], PASSWORD_DEFAULT));
                        //éxecution
                        $em->flush();

                        }
                        else if ($userProToChange) {
                            $userProToChange->setPassword(password_hash($safe['password'], PASSWORD_DEFAULT));
                        //éxecution
                        $em->flush();
                        }


                    $success = true;

                    }    
            }
            return $this->render('new-password.html.twig', [
                'mes_erreurs'       => $errors,
                'success'           => $success,
            ]);
        }

}



