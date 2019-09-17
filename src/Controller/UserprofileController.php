<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

use Respect\Validation\Validator as v;

use Doctrine\ORM\EntityManagerInterface; //Connexion à la base données

use App\Entity\UserPro; // Intéraction
use App\Entity\User; // Intéraction


class UserprofileController extends AbstractController
{
    /**
     * @Route("/userprofile", name="userprofile")
     */
    public function userprofile()
    {
        $session = new Session();

        // Je place mes erreurs dans un tableau
        $errors = [];

        $success = '';


        // Si mes inputs sont remplies
        if (!empty($_POST)) {

            
            $safe = array_map('trim', array_map('strip_tags', $_POST));


            $errors = [
                (!v::notEmpty()->length(3,15)->validate($safe['firstname'])) ? 'Votre prénom doit comporter entre 3 et 15 caractères' : null,
                (!v::notEmpty()->length(3,15)->validate($safe['lastname'])) ? 'Votre nom doit comporter entre 3 et 15 caractères' : null,
                ];

                if ($session->get('pro') == 'oui'){
                    $errors = [(!v::notEmpty()->length(9,9)->validate($safe['siren'])) ? 'Votre siren doit comporter 9 caractères' : null,];
                } 

                if(password_verify($safe["password"],$session->get('password'))){
                    $errors=[(!v::notEmpty()->length(5,15)->validate($safe['password'])) ? 'Votre mot de passe doit comporter entre 3 et 15 caractères' : null,];
                }



            if (!empty($safe['email'])) {
                if(!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Votre adresse email n\'est pas valide';
                }
            }
            else $errors[] = 'Le champ Adresse Email est obligatoire';


            if (count($errors) == 0) {

               
				$success = true;
            
            } // Fin de 'if (count($errors) == 0)'

        } // Fin de 'if (!empty($_POST))'

        $em = $this->getDoctrine()->getRepository(UserPro::class);

        $users = $em->findBy(['id' => 1]);

        return $this->render('userprofile/user-profile.html.twig', [
            'success'   => $success ?? null,
            'users'     => $users,
            'mes_erreurs' => $errors,
        ]);
    }
}
