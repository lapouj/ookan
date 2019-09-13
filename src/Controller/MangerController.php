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

    	$success = false;

    	if(!empty($_POST)){
    		// Nettoyage des données
    		$safe = array_map('trim', array_map('strip_tags', $_POST));

    		if (strlen($safe['nom']) <= 4) {
    			$errors[] = 'Votre nom doit contenir au moins 4 caractères';
    		}

              if (!isset($safe['type'])) {
                $errors[] = 'Votre de choisir un type';
            }

    		if (strlen($safe['description']) <= 50) {
    			$errors[] = 'Votre description doit contenir au moins 50 caractères';
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

                // On enlève un éventuel http ou https pour pouvoir le remettre à l'affichage.
                if(!empty($safe['internet'])){
                    $web_site = str_replace(['http://', 'https://'], '', $safe['internet']);
                }

    			$restoData = new Resto(); 
    			$restoData   ->setRestoName($safe['nom'])
    			->setDescription($safe['description'])
    			->setType($safe['type'])
    			->setStreetname($safe['street_name'])
    			->setStreetnum($safe['street_num'])
    			->setCp($safe['cp'])
                ->setPhone($safe['phone'])
                ->setWebsite($web_site ?? '')
    			->setVille($safe['ville']);
    			// On prépare la requete.
    			$em->persist($restoData);
    			// On l'exécute
    			$em->flush();

                $success = true;

    		}
    	}    

    	return $this->render('manger/ajouter.html.twig', [
    		'mes_erreurs'     =>  $errors,
            'success' => $success,
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
