<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

use Respect\Validation\Validator as v;

use Doctrine\ORM\EntityManagerInterface; //Connexion à la base données

use App\Entity\UserPro; // Intéraction
use App\Entity\User; // Intéraction

use \Behat\Transliterator\Transliterator as tr;
use \Intervention\Image\ImageManagerStatic as Image;


class UserprofileController extends AbstractController
{
    /**
     * @Route("/userprofile", name="userprofile")
     */
    public function userprofile()
    {
        $session = new Session();
        $pro_connected = $session->get('pro');

        // Je place mes erreurs dans un tableau
        $errors = [];
        $errorsSiren = [];
        $totalerrors = [];

        $success = false;


        // $userFound contient les informations de mon utilisateur qui sont en base de données
        $em = $this->getDoctrine()->getManager();

        $userFound = $em->getRepository(User::class)->find( $session->get('user_id'));
        $userProFound = $em->getRepository(UserPro::class)->find( $session->get('user_id'));



           



        // Si mes inputs sont remplies
        if (!empty($_POST)) {


            $safe = array_map('trim', array_map('strip_tags', $_POST));


            $errors = [
                (!v::notEmpty()->length(3,15)->validate($safe['firstname'])) ? 'Votre prénom doit comporter entre 3 et 15 caractères' : null,
                (!v::notEmpty()->length(3,15)->validate($safe['lastname'])) ? 'Votre nom doit comporter entre 3 et 15 caractères' : null,
            ];
            

            if ($pro_connected == 'oui'){
                $errorsSiren = [
                    (!v::notEmpty()->length(9,9)->validate($safe['siren'])) ? 'Votre siren doit comporter 9 caractères' : null,
                ];
            } 

            // if(password_verify($safe["password"], $userFound['password'])){
            //     $errors[] = (!v::notEmpty()->length(5,15)->validate($safe['password'])) ? 'Votre mot de passe doit comporter entre 3 et 15 caractères' : null;
            // }



            if (!empty($safe['email'])) {
                if(!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Votre adresse email n\'est pas valide';
                }
            }
            else {
                $errors[] = 'Le champ Adresse Email est obligatoire';
            } 

            $errors = array_filter($errors);
            $errorsSiren = array_filter($errorsSiren);
            $totalerrors = array_merge($errors, $errorsSiren);



            if (count($totalerrors) == 0) {
                $success = true;



                if ($pro_connected == 'oui'){
                    $userProFound->setFirstname($safe['firstname'])
                                ->setName($safe['lastname'])
                                ->setEmail($safe['email'])  
                                ->setSiret($safe['siren'])
                                ->setPseudo($userFound->getPseudo())
                                ->setPassword(password_hash($safe['password'], PASSWORD_DEFAULT));
                    //éxecution
                    $em->flush();
                }
               
                $session = new Session();
                $session->set('user_id',  $userProFound->getId());
                $session->set('pseudo',  $userProFound->getPseudo());
                $session->set('email',  $userProFound->getEmail());
                $session->set('firstname',  $userProFound->getFirstname());
                $session->set('lastname',  $userProFound->getName());
                $session->set('siret',  $userProFound->getSiret());
                $session->set('pro', 'oui');
                $session->set('connected', 'true');
                $session->set('password', $userProFound->getPassword());
            
                return $this->render('userprofile/user-profile.html.twig', [
                    'success'   => $success,
                    'liste_erreurs' => $totalerrors,
                ]);
            } // Fin de 'if (count($errors) == 0)'

        } // Fin de 'if (!empty($_POST))'


        return $this->render('userprofile/user-profile.html.twig', [
            'success'   => $success,
            'liste_erreurs' => $totalerrors,
        ]);
    }
}
