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

        // Création des tableaux d'erreurs
        $errors = [];
        $errorsSiren = [];
        $errorsPassword = [];
        $checkpassword = [];
        $errorsImage = [];
        $totalerrors = [];

        $success = false;
        $successImage = false;

        //Préparation pour l'image :
        $maxSizeFile = 3 * 1000 * 1000; //3mo max
        $uploadDir = 'img/uploaded/profiles';
        $allowMimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];


        // $userFound contient les informations de mon utilisateur qui sont en base de données
        $em = $this->getDoctrine()->getManager();

        $userFound = $em->getRepository(User::class)->find( $session->get('user_id'));
        $userProFound = $em->getRepository(UserPro::class)->find( $session->get('user_id'));


        //Si j'ai choisi un fichier
         if (!empty($_POST)&&(!empty($_FILES))) {
             //Upload de l'image :
            if (!empty($_FILES['avatar'])) {
                if ($_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
                    $image = Image::make($_FILES['avatar']['tmp_name']);
                    if ($image->filesize() > $maxSizeFile) {
                        $errorsImage[] = 'Votre image ne doit pas excedér 3 Mo';
                    } elseif (!v::in($allowMimes)->validate($image->mime())) {
                        $errorsImage[] = 'Votre fichier n\'est pas une image valide';
                    }
                } elseif ($_FILES['avatar']['error'] == UPLOAD_ERR_NO_FILE) {
                    $errorsImage[] = 'Aucun fichier n\'a été uploadé';
                } else {
                    $errorsImage[] = 'Une erreur est survenue lors de l\'envoi de l\'image';
                }
            }

            if (count($errorsImage) == 0){
                $successImage = true;
            }

        }




        // Si mes inputs sont remplies
        if (!empty($_POST)&&(empty($_FILES))) {


            $safe = array_map('trim', array_map('strip_tags', $_POST));


            $errors = [
                (!v::notEmpty()->length(2,15)->validate($safe['firstname'])) ? 'Votre prénom doit comporter entre 2 et 15 caractères' : null,
                (!v::notEmpty()->length(2,15)->validate($safe['lastname'])) ? 'Votre nom doit comporter entre 2 et 15 caractères' : null,
            ];
            

            if (($safe['password'] != $safe['confirm-password']) && (!empty($safe['password']))) {
                $checkpassword[] = 'Erreur lors de la confirmation du nouveau mot de passe';
            }

            if ((((strlen($safe['password'])<3) || (strlen($safe['password'])>15))) && (!empty($safe['password']))) {
                $checkpassword[] = 'Votre mot de passe doit comporter entre 3 et 15 caractères';
            }


            if ($pro_connected == 'oui'){
                $errorsSiren = [
                    (!v::notEmpty()->length(9,9)->validate($safe['siren'])) ? 'Votre siren doit comporter 9 caractères' : null,
                ];

                //Check du mdp dans BDD user_pro
                if(!password_verify($safe["lastpassword"], $userProFound->getPassword())){
                    $errorsPassword[] = 'Ancien mot de passe incorrect';
                }
            }//fin de if Pro_connected


            else if ($pro_connected == 'non'){
                 if(!password_verify($safe["lastpassword"], $userFound->getPassword())){
                    $errorsPassword[] = 'Ancien mot de passe incorrect';
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


            // Récap des erreurs
            $errors = array_filter($errors);
            $errorsSiren = array_filter($errorsSiren);
            $totalerrors = array_merge($errors, $errorsSiren, $errorsPassword, $checkpassword);

            if (count($totalerrors) == 0) {
                $success = true;


            if ($pro_connected == 'oui'){
                $userProFound->setFirstname($safe['firstname'])
                             ->setName($safe['lastname'])
                             ->setEmail($safe['email'])  
                             ->setSiret($safe['siren'])
                             ->setPseudo($userProFound->getPseudo());
                if(!empty($safe['password'])){
                    $userProFound->setPassword(password_hash($safe['password'], PASSWORD_DEFAULT));
                };
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


                else if ($pro_connected == 'non'){

                    $userFound->setFistname($safe['firstname'])
                              ->setName($safe['lastname'])
                              ->setEmail($safe['email']) 
                              ->setPseudo($userFound->getPseudo());
                    if(!empty($safe['password'])){
                        $userFound->setPassword(password_hash($safe['password']   , PASSWORD_DEFAULT));
                    }

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

            } // Fin de 'if (count($errors) == 0)'

        } // Fin de 'if (!empty($_POST))'


                if ($pro_connected == 'non') {
                    return $this->render('userprofile/user-profile.html.twig', [
                        'success'       => $success,
                        'successImage'  => $successImage,
                        'liste_erreurs' => $totalerrors,
                        'info_user'     => $userFound,
                        'erreurs_image' => $errorsImage,
                    ]);

                }
                else if ($pro_connected == 'oui'){
                    return $this->render('userprofile/user-profile.html.twig', [
                        'success'       => $success,
                        'successImage'  => $successImage,
                        'liste_erreurs' => $totalerrors,
                        'info_user'     => $userProFound,
                        'erreurs_image' => $errorsImage,
                    ]);
                }
        // return $this->render('userprofile/user-profile.html.twig', [
        //     'success'       => $success,
        //     'liste_erreurs' => $totalerrors,
        // ]);
    }     
}
