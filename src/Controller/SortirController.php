<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sortie;

class SortirController extends AbstractController
{
    /**
     * @Route("/sortir", name="sortir")
     */
    public function index()
    {
    	return $this->render('sortir/index.html.twig', [
    		'controller_name' => 'SortirController',
    	]);
    }

    public function add()
    {

    	$errors = [];

    	$success = '';

    	if(!empty($_POST)){
    		
    		$safe = array_map('trim', array_map('strip_tags', $_POST));




            if (strlen($safe['nom']) <= 4) {
                $errors[] = 'Votre nom doit contenir au moins 4 caractères';
            }

            if (strlen($safe['description']) <= 50) {
                $errors[] = 'Votre description doit contenir au moins 50 caractères';
            }

            if (!isset($safe['type'])) {
                $errors[] = 'Votre de choisir un type';
            }

            if (!is_numeric($safe['street_num'])) {
                $errors[] = 'Merci d\'indiquer un numéro de rue valide (Pas de texte)';
            }

            if (strlen($safe['street_name']) <= 5) {
                $errors[] = 'Votre nom de rue doit contenir au moins 5 caractères';
            }

            if (strlen($safe['cp']) != 5) {
                $errors[] = 'Merci d\'indiquer un code postal valide';
            }

            if (!isset($safe['ville'])) {
                $errors[] = 'Merci d\'indiquer une ville';
            }




            if (count($errors) == 0) {
            
    		// Utilisation de la base de données
    			$em = $this->getDoctrine()->getManager();

    			$sortieData = new Sortie(); 
    			$sortieData   ->setSortieName($safe['nom'])
    			->setDescription($safe['description'])
    			->setType($safe['type'])
    			->setStreetname($safe['street_name'])
    			->setStreetnum($safe['street_num'])
    			->setCp($safe['cp'])
    			->setVille($safe['ville']);
    			// On prépare la requete.
    			$em->persist($sortieData);
    			// On l'exécute
    			$em->flush();
    	}    
}
    	return $this->render('sortir/ajouterSorti.html.twig', [
    		'mes_erreurs'     =>  $errors,
    	]);
    }

    public function show()
    {
    	{

            // Récupération de l'article
    		$em = $this->getDoctrine()->getManager();
            // Permet de chercher les articles données via le repository
    		$sortiesFound = $em->getRepository(sortie::class)->findAll();

            // la vue
    		return $this->render('sortir/afficherSorti.html.twig', 
    			['sorties'=> $sortiesFound,
    		]);
    	}

    	return $this->render('sortir/afficherSorti.html.twig', [
    		'controller_name' => 'MangerController',
    	]);
    }
}







