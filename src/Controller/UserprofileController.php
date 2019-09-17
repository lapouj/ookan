<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

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
        
        // Je place mes erreurs dans un tableau
        $errors = [];

        $success = '';

        $errors = $errorsForm = $errorsImg = [];
        $maxSizeFile = 3 * 1000 * 1000; 
        $uploadDir = '../image_compte/';
        $allowMimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];

        // Si mes inputs sont remplies
        if (!empty($_POST)) {

            // je nettoie les données reçues
            $safe = array_map('trim', array_map('strip_tags', $_POST));

            // Je pose mes conditions de validation du formulaire
            if (!empty($safe['firstname'])) {
                if (strlen($safe['firstname']) <= 1) {
                    $errors[] = 'Votre prénom doit comporter au moins 2 caractères';
                }
            } else $errors[] = 'Le champ Prénom est obligatoire';

            if (!empty($safe['lastname'])) {
                if (strlen($safe['lastname']) <= 1) {
                    $errors[] = 'Votre Nom doit comporter au moins 2 caractères';
                }
            } else $errors[] = 'Le champ Nom est obligatoire';

            if (!empty($safe['siren'])) {
                if (is_numeric($safe['siren'])) {
                    if (strlen($safe['siren']) != 9) {
                        $errors[] = 'Le N° SIREN doit être composé de 9 chiffres';
                    }
                } else $errors[] = 'Le champ N° SIREN doit contenir une valeur numérique';
            } else $errors[] = 'Le champ N° SIREN est obligatoire';

            if (!empty($safe['email'])) {
                if(!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Votre adresse email n\'est pas valide';
                }
            } else $errors[] = 'Le champ Adresse Email est obligatoire';

            if (!empty($safe['lastpassword'])) {
                
            } else $errors[] = 'Le champ Ancien mot de passe est obligatoire';

            if (!empty($safe['password'])) {
                if (strlen($safe['password']) < 8) {
                    $errors[] = 'Votre mot de passe doit contenir au moins 8 caractères';
                }
            } else $errors[] = 'Le champ Mot de passe est obligatoire';

            if ($safe['password'] != $safe['confirm-password']) {
                $errors[] = 'Vos mot de passe ne sont pas identiques';
            }

        if (!empty($_FILES['avatar'])) {
            if ($_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
                
                $img = Image::make($_FILES['avatar']['tmp_name']);

                if ($img->filesize() > $maxSizeFile) {
                    $errorsImg[] = 'Votre image ne doit pas dépasser 3 Mo';
                }
                elseif (!v::in($allowMimes)->validate($img->mime())) {
                    $errorsImg[] = 'Votre image n\'est pas valide';
                }
            }
            elseif($_FILES['avatar']['error'] == UPLOAD_ERR_NO_FILE){
                $errorsImg[] = 'Aucune photo n\'à été selectionner';
            }
            else {
                $errorsImg[] = 'Une erreur est survenue lors de l\'envoie de l\'image';
            }
        }

        $pathInfo = pathinfo($_FILES['avatar']['name']);

            
            if (count($errors) == 0) {

                $errors = array_filter($errors);

                $em = $this->getDoctrine()->getManager();

                $userData = getUserPro();

                $userData->setPhoto($_FILES['photo']);

                //Préparation de la requete.
                $em->persist($userData);
                //éxecution
                $em->flush();
                
                // Redirection
				$success = 'Votre profil a été mis à jour!';
            
            } // Fin de 'if (count($errors) == 0)'

        } // Fin de 'if (!empty($_POST))'

        $em = $this->getDoctrine()->getRepository(UserPro::class);

        $users = $em->findBy(['id' => 1]);

        return $this->render('userprofile/user-profile.html.twig', [
            'success'   => $success ?? null,
            'users'     => $users,
        ]);
    }
}
