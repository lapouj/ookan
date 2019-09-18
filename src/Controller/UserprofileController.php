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

/* import des classes de PHPMailer */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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
        $errorsPassword = [];
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

                //Check du mdp dans BDD user_pro
                if(!password_verify($safe["lastpassword"], $userProFound->getPassword())){
                    $errorsPassword[] = 'Le mot de passe ne correspond pas';
                }
            }//fin de if Pro_connected


            else if ($pro_connected == 'non'){
             if(!password_verify($safe["lastpassword"], $userFound->getPassword())){
                $errorsPassword[] = 'Le mot de passe ne correspond pas';
            }

        }




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
        $totalerrors = array_merge($errors, $errorsSiren, $errorsPassword);

        if (count($totalerrors) == 0) {
            $success = true;


            if ($pro_connected == 'oui'){
                $userProFound->setFirstname($safe['firstname'])
                ->setName($safe['lastname'])
                ->setEmail($safe['email'])  
                ->setSiret($safe['siren'])
                ->setPseudo($userProFound->getPseudo())
                ->setPassword(password_hash($safe['password'], PASSWORD_DEFAULT));
                    //éxecution
                $em->flush();

                $session = new Session();
                $session->set('user_id',  $userProFound->getId());
                $session->set('pseudo',  $userProFound->getPseudo());
                $session->set('email',  $userProFound->getEmail());
                $session->set('firstname',  $userProFound->getFirstname());
                $session->set('lastname',  $userProFound->getName());
                $session->set('siret',  $userProFound->getSiret());
                $session->set('pro', 'oui');
                $session->set('connected', 'true');
                

                }//fin pro_connected


            $test = count($totalerrors);

                if ($pro_connected == 'non'){
                    $userFound->setFistname($safe['firstname'])
                    ->setName($safe['lastname'])
                    ->setEmail($safe['email']) 
                    ->setPseudo($userFound->getPseudo())
                    ->setPassword(password_hash($safe['password'], PASSWORD_DEFAULT));
                    //éxecution
                    $em->flush();

                    $session = new Session();
                    $session->set('user_id',  $userFound->getId());
                    $session->set('pseudo',  $userFound->getPseudo());
                    $session->set('email',  $userFound->getEmail());
                    $session->set('firstname',  $userFound->getFistname());
                    $session->set('lastname',  $userFound->getName());
                    $session->set('pro', 'oui');
                    $session->set('connected', 'true');



                }//fin user normal connected


                //Manque à faire basculer les info BDD des $userfound et $userprofound dans le twig.


                if ($pro_connected == 'non') {
                    return $this->render('userprofile/user-profile.html.twig', [
                        'success'   => $success,
                        'liste_erreurs' => $totalerrors,
                        'info_user' => $userFound,
                    ]);

                }
                else if ($pro_connected == 'oui'){
                    return $this->render('userprofile/user-profile.html.twig', [
                        'success'   => $success,
                        'liste_erreurs' => $totalerrors,
                        'info_user' => $userProFound,
                    ]);
                }
            } // Fin de 'if (count($errors) == 0)'

        } // Fin de 'if (!empty($_POST))'


        return $this->render('userprofile/user-profile.html.twig', [
            'success'       => $success,
            'liste_erreurs' => $totalerrors,
        ]);
    }     
}
