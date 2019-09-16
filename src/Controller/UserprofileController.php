<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

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
        
        // Je place mes erreurs dans un tableau
        $errors = [];

        $success = '';

        // Si mes inputs sont remplies
        if (!empty($_POST)) {

            // je nettoie les données reçues
            $safe = array_map('trim', array_map('strip_tags', $_POST));
            
            if (!empty($_FILES['avatar']['name'])) {
                $maxsize = 2097152;
                $extensionValide = array('jpg', 'jpeg', 'png');
                if ($_FILES['avatar']['size'] <= $taillemax) {
                    $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
                    if (in_array($extensionUpload, $extensionValide)) {
                        $chemin = '/img/profil/'.$_SESSION['id'].'.'.$extensionUpload;
                        $result = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                        if ($result) {
                            $updateAvatar = $this->getDoctrine()->getManager(UserPro::class);
                        } else $errors[] = 'Erreur durant l\'importation du fichier';
                    } else $errors[] = 'Votre photo de profil doit être au format jpg, jpeg ou png';
                } else $errors[] = 'Votre photo de profil ne doit pas dépasser 2Mo';
            }

            if (count($errors) == 0) {

                $errors = array_filter($errors);

                $em = $this->getDoctrine()->getManager();

                $userData = new UserPro();

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
            'users'     => $users,
        ]);
    }
}
