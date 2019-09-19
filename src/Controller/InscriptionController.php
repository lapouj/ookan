<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use Doctrine\ORM\EntityManagerInterface; //Connexion à la base données

use App\Entity\User; // Intéraction
use App\Entity\UserPro; // Intéraction

use Symfony\Component\HttpFoundation\Session\Session;


class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription_choix_profil()
    {
        return $this->render('inscription/inscription-choix.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function inscription_particulier()
    {
    	$errors = [];

    	$success = '';

    	if (!empty($_POST)) {

    		// je nettoie les données reçues
    		$safe = array_map('trim', array_map('strip_tags', $_POST));

            if (strlen($safe['nom']) <= 1) {
                $errors[] = 'Votre nom doit contenir au moins 2 caractères';
            }

            if (strlen($safe['prenom']) <= 1) {
                $errors[] = 'Votre prénom doit contenir au moins 2 caractères';
            }

            if (strlen($safe['pseudo']) <= 5) {
                $errors[] = 'Votre pseudo doit contenir au moins 6 caractères';
            }

            if(!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Votre adresse email n\'est pas valide';
            }

            if (strlen($safe['password']) < 4) {
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

            if (!empty($safe['pseudo'])) {
                if (strlen($safe['pseudo']) <= 1) {
                    $errors[] = 'Votre Pseudo doit comporter au moins 2 caractères';
                }
            } else $errors[] = 'Le champ Pseudo est obligatoire';

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

            if (!empty($safe['password'])) {
                if (strlen($safe['password']) < 8) {
                    $errors[] = 'Votre mot de passe doit contenir au moins 8 caractères';
                }
            } else $errors[] = 'Le champ Mot de passe est obligatoire';

            if ($safe['password'] != $safe['confirm-password']) {
                $errors[] = 'Vos mot de passe ne sont pas identiques';
            }

            $emailExist = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $safe['email']]);
            $emailProExist = $this->getDoctrine()->getRepository(UserPro::class)->findBy(['email' => $safe['email']]);
            if(!empty($emailExist) || !empty($emailProExist)){
              $errors[] = 'L\'adresse email existe déjà';
            }

            if (count($errors) == 0) {

                $errors = array_filter($errors);

                $em = $this->getDoctrine()->getManager();

                $userData = new UserPro();

                $userData->setFirstname($safe['firstname'])
                        ->setName($safe['lastname'])
                        ->setPseudo($safe['pseudo'])
                        ->setSiret($safe['siren'])
                        ->setEmail($safe['email'])	
                        ->setPassword(password_hash($safe['password'], PASSWORD_DEFAULT));

                //Préparation de la requete.
                $em->persist($userData);
                //éxecution
                $em->flush();
                
                // Redirection
                return $this->redirectToRoute('inscription_reussite');
				// $success = 'Votre inscription est un succès, bienvenue chez Ookan !';
            
            } // Fin de 'if (count($errors) == 0)'

        } // Fin de 'if (!empty($_POST))'

        return $this->render('inscription/inscription-pro.html.twig', [
                'mes_erreurs'     =>  $errors,
                // 'mes_validation'  =>  $success ?? null,
        ]);
    }
    public function inscription_reussite()
    {
        return $this->render('inscription/inscription-reussite.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
