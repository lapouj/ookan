<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; //Connexion à la base données

use App\Entity\User; // Intéraction
use App\Entity\UserPro; // Intéraction
use Symfony\Component\HttpFoundation\Session\Session;

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

                $errors = array_filter($errors);

                if(!empty($userdata)){

                $session = new Session();
                $session->set('pseudo',  $userdata->getPseudo());
                $session->set('email',  $userdata->getEmail());
                $session->set('firstname',  $userdata->getFistname());
                $session->set('lastname',  $userdata->getName());
                $session->set('pro', 'non'),
                $session->set('connected', 'true');

                return $this->redirectToRoute('user_profile');

                }  
                else if (!empty($userdatapro)){

                $session = new Session();
                $session->set('pseudo',  $userdatapro->getPseudo());
                $session->set('email',  $userdatapro->getEmail());
                $session->set('firstname',  $userdatapro->getFirstname());
                $session->set('lastname',  $userdatapro->getName());
                $session->set('pro', 'oui');

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

}



