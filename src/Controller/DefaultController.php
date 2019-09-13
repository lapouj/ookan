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

        if (!empty($_POST['email']) || !empty($_POST['password'])) {

            $safe = array_map('trim', array_map('strip_tags', $_POST));

            if (!empty($safe['email'])) {

                if(!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {

                    $errors[] = 'Votre adresse email n\'est pas valide';
                }
            } else $errors[] = 'Le champ Adresse Email est obligatoire';   


            if (!empty($safe['password'])) {

                $errors[] = 'Veuillez saisir votre mot de passe';
            } 


            $my_user_name = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $safe['email']]);

            $mailfound = 0;

            if ($my_user_name) {
                $mailfound = $my_user_name->getEmail(); 
            }

            if ($mailfound===0) {
                $errors[] = 'Utilisateur introuvable';
            }
           
      


            if (count($errors) == 0) {

                $errors = array_filter($errors);

                
                if(!empty($my_user_name) AND (!empty($my_user_password))){

                    $infos_session = [
                        'id'            => $my_user_name->getId(),
                        'email'         => $my_user_name->getEmail(),
                        'username'      => $my_user_name->getUsername(),
                        'password'      => $my_user_name->getPassword(),
                    ];

                    $session = new Session();
                    $session->set('user', $infos_session);

                    
                    // Affichage (exemple)
                    /*$user_en_session = $session->get('user');
                    echo $user_en_session['email']; */
                    return $this->redirectToRoute('user_profile');
                }  
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



