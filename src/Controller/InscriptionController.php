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
    	$success = false;

    	if (!empty($_POST)) {
    		// je nettoie les données reçues
    		$safe = array_map('trim', array_map('strip_tags', $_POST));

    	if (strlen($safe['nom']) < 2) {
    		$errors[] = 'Votre nom doit contenir au moins 3 caractères';
    	}

    	if (strlen($safe['prenom']) < 4) {
    		$errors[] = 'Votre prénom doit contenir au moins 5 caractères';
    	}

    	if (strlen($safe['pseudo']) < 4) {
    		$errors[] = 'Votre pseudo doit contenir au moins 5 caractères';
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

    	if (count($errors) == 0) {
    	
    	$success = true;

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

	   }
	}
    
        return $this->render('inscription/inscription-particulier.html.twig', [
        	'mes_erreurs'     =>  $errors,
        	'mes_validation'  =>  $success,
            'controller_name' => 'DefaultController',
        ]);
    }
}
