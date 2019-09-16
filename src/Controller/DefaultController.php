<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; //Connexion à la base données
use App\Entity\User; // Intéraction
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

            // if (empty($safe['password'])) {

            // $errors[] = 'Veuillez saisir votre mot de passe';

            // } 


            $userdata = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $safe['email']]);

            $mailfound = 0;

            if ($userdata){
                $mailfound = $userdata->getEmail();
            }
            else{
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



