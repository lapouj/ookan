<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User; // Intéraction

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
        
            $userData = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $safe['email']]);
            
            foreach ($$_POST as $key => $value) {
                $post[$key] = trim(strip_tags($value));
            }

            if (empty($post['email'])) {
                $errors[] = 'Veuillez saisir votre email';
            }

            if (empty($post['password'])) {
                $errors[] = 'Veuillez saisir votre mot de passe';
            }

            if (count($errors) == 0) {
                if (password_verify($post['password'], $userData->getPwd())) {

                    $_SESSION['user'] = $userData;
                    session_regenerate_id();                    
                    $mes_erreurs = true;
                    
                    $em->persist($userData);
                    //éxecution
                    $em->flush();
                    //return $this->redirectToRoute('accueil');
                
                    $success = 'Connexion réussi !';

                }


            } else {

                $errors[] = 'Le mot de passe ne correspond pas';
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
}
