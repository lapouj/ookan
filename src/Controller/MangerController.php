<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Resto;

class MangerController extends AbstractController
{
    /**
     * @Route("/manger", name="manger")
     */
    public function manger()
    {
    	return $this->render('manger/index.html.twig', [
    		'controller_name' => 'MangerController',
    	]);
    }

    public function add()
    {

    	$errors = [];

    	$success = '';

    	if(!empty($_POST)){
    		// Nettoyage des données
    		$safe = array_map('trim', array_map('strip_tags', $_POST));

    		if (strlen($safe['nom']) <= 4) {
    			$errors[] = 'Votre nom doit contenir au moins 4 caractères';
    		}

    		if (strlen($safe['description']) <= 50) {
    			$errors[] = 'Votre description doit contenir au moins 50 caractères';
    		}

    		if (!is_numeric($safe['street_num'])) {
    			$errors[] = 'Merci d\'indiquer un numéro de rue valide (Pas de texte)';
    		}


    		if (count($errors) == 0) {
    		// Utilisation de la base de données
    			$em = $this->getDoctrine()->getManager();

    			$restoData = new Resto(); 
    			$restoData   ->setRestoName($safe['nom'])
    			->setDescription($safe['description'])
    			->setType($safe['type'])
    			->setStreetname($safe['street_name'])
    			->setStreetnum($safe['street_num'])
    			->setCp($safe['cp'])
    			->setVille($safe['ville']);
    			// On prépare la requete.
    			$em->persist($restoData);
    			// On l'exécute
    			$em->flush();

    		}
    	}    

    	return $this->render('manger/ajouter.html.twig', [
    		'mes_erreurs'     =>  $errors,
    	]);
    }











    public function show()
    {
    	{

            // Récupération de l'article
    		$em = $this->getDoctrine()->getManager();
            // Permet de chercher les articles données via le repository
    		$restoFound = $em->getRepository(resto::class)->findAll();

            // la vue
    		return $this->render('manger/afficher.html.twig', 
    			['resto'=> $restoFound,
    		]);
    	}

    	return $this->render('manger/afficher.html.twig', [
    		'controller_name' => 'MangerController',
    	]);
    }
}
