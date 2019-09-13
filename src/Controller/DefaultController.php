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

        $success = '';
        

        if (!empty($_POST['email']) && !empty($_POST['password'])) {

            $safe = array_map('trim', array_map('strip_tags', $_POST));
        
            
            foreach ($_POST as $key => $value) {
                $post[$key] = trim(strip_tags($value));
            }

            if (empty($post['email'])) {
                $errors[] = 'Veuillez saisir votre email';
            }

            if (empty($post['password'])) {
                $errors[] = 'Veuillez saisir votre mot de passe';
            }

            if (count($errors) == 0) {
            
                $my_user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $safe['email']]);


                if(!empty($my_user)){

                    $infos_session = [
                        'id'            => $my_user->getId(),
                        'email'         => $my_user->getEmail(),
                        'username'      => $my_user->getUsername(),
                    ];

                    $session = new Session();
                    $session->set('user', $infos_session);

                    
                    // Affichage (exemple)
                    /*$user_en_session = $session->get('user');
                    echo $user_en_session['email']; */
                    

                    $mes_erreurs = true;
                    $success = 'Connexion réussi !';
                
                } 

                   return $this->redirectToRoute('user_profile');

            } 
        }
        return $this->render('connexion.html.twig', [
            
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


                   

