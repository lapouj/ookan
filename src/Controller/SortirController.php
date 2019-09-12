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
    		
    		// Utilisation de la base de données
    			$em = $this->getDoctrine()->getManager();

    			$sortieData = new Sortie(); 
    			$sortieData   ->setSortieName($safe['nom'])
    			->setDescription($safe['description'])
    			->setType($safe['type'])
    			->setStreetname($safe['street_name'])
    			->setStreetnum($safe['street_num'])
    			->setCp($safe['cp'])
    			->setPhone($safe['phone'])
    			->setWebsite($safe['internet'])
    			->setVille($safe['ville']);
    			// On prépare la requete.
    			$em->persist($sortieData);
    			// On l'exécute
    			$em->flush();

    		
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







