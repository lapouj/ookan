<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface; //Connexion à la base données
use App\Entity\User; // Intéraction

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

            if (strlen($safe['nom']) <= 2) {
                $errors[] = 'Votre nom doit contenir au moins 3 caractères';
            }

            if (strlen($safe['prenom']) <= 4) {
                $errors[] = 'Votre prénom doit contenir au moins 5 caractères';
            }

            if (strlen($safe['pseudo']) <= 4) {
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

            if (count($errors) == 0) {
            
      

                $errors = array_filter($errors);

                $em = $this->getDoctrine()->getManager();

                $userData = new User();
                $userData->setName($safe['nom'])
                        ->setPseudo($safe['pseudo'])
                        ->setFirstname($safe['prenom'])
                        ->setMail($safe['email'])	
                        ->setPassword(password_hash($safe['password'], PASSWORD_DEFAULT));

                //Préparation de la requete.
                $em->persist($userData);
                //éxecution
                $em->flush();
				
				$success = 'Votre inscription est un succès, bienvenue chez Ookan !';
            }
	    	
	    } 
    
        return $this->render('inscription/inscription-particulier.html.twig', [
        	'mes_erreurs'     =>  $errors,
        	'mes_validation'  =>  $success,
        ]);
     }	

    public function inscription_pro()
    {
        // Je place mes erreurs dans un tableau
        $errors = [];

        // Si mes inputs sont remplies
        if (!empty($_POST)) {

            // je nettoie les données reçues
            $safe = array_map('trim', array_map('strip_tags', $_POST));
            
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

        } // Fin de 'if (!empty($_POST))'

        return $this->render('inscription/inscription-pro.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    public function inscription_reussite()
    {
        return $this->render('inscription/inscription-reussite.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
