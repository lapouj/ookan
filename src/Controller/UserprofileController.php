<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
        $errors = [];

    	$success = '';

    	if (!empty($_POST)) {

    		// je nettoie les données reçues
    		$safe = array_map('trim', array_map('strip_tags', $_POST));

            if (strlen($safe['nom']) <= 3) {
                $errors[] = 'Votre nom doit contenir au moins 4 caractères';
            }

            if (strlen($safe['prenom']) <= 3) {
                $errors[] = 'Votre prénom doit contenir au moins 5 caractères';
            }

            if (strlen($safe['pseudo']) <= 3) {
                $errors[] = 'Votre pseudo doit contenir au moins 5 caractères';
            }

            if(!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Votre adresse email n\'est pas valide';
            }

            if (strlen($safe['password']) <= 4) {
                $errors[] = 'Votre mot de passe doit contenir au moins 5 caractères';
            }
            elseif($safe['password'] != $safe['comfirm_password']) {
                $errors[] = 'Votre mot de passe n\'est pas identique';
            }

            $emailExist = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $safe['email']]);
            $emailProExist = $this->getDoctrine()->getRepository(UserPro::class)->findBy(['email' => $safe['email']]);
            if(!empty($emailExist) || (!empty($emailProExist))){
              $errors[] = 'L\'adresse email existe déjà';
            }

            $pseudoExist = $this->getDoctrine()->getRepository(User::class)->findBy(['pseudo' => $safe['pseudo']]);
            if(!empty($pseudoExist)){
                $errors[] = 'Ce pseudo est déjà utilisé';
            }

            if (count($errors) == 0) {
            
      

                $errors = array_filter($errors);

                $em = $this->getDoctrine()->getManager();

                $userData = new User();
                $userData->setName($safe['nom'])
                         ->setPseudo($safe['pseudo'])
                         ->setFistname($safe['prenom'])
                         ->setEmail($safe['email'])	
                         ->setPassword(password_hash($safe['password'], PASSWORD_DEFAULT));

                //Préparation de la requete.
                $em->persist($userData);
                //éxecution
                $em->flush();
                
                // Redirection
                return $this->redirectToRoute('inscription_reussite');
				// $success = 'Votre inscription est un succès, bienvenue chez Ookan !';
            }
	    	
	    } 
    
        return $this->render('inscription/inscription-particulier.html.twig', [
        	'mes_erreurs'     =>  $errors,
        	// 'mes_validation'  =>  $success,
        ]);
     }	

    public function inscription_pro()
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
