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

    	if(!empty($_POST)){
    		// Nettoyage des données
    		$safe = array_map('trim', array_map('strip_tags', $_POST));



    			// Utilisation de la base de données
    			$em = $this->getDoctrine()->getManager();

    			/* $articlesData me permet d'utiliser les méthodes de la class App\Entity\Articles.php */
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
    	





















































































    	return $this->render('manger/ajouter.html.twig', [
    		
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
