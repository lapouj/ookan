<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Resto; // Intéraction
use App\Entity\Comments; // Intéraction
use Symfony\Component\HttpFoundation\Session\Session;

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

    		if (strlen($safe['nom']) < 4) {
    			$errors[] = 'Votre nom doit contenir au moins 4 caractères';
    		}

              if (!isset($safe['type'])) {
                $errors[] = 'Votre de choisir un type';
            }

    		if (strlen($safe['description']) < 50) {
    			$errors[] = 'Votre description doit contenir au moins 50 caractères';
    		}

    		if (!is_numeric($safe['street_num'])) {
    			$errors[] = 'Merci d\'indiquer un numéro de rue valide (Pas de texte)';
    		}

            if (strlen($safe['street_name']) < 5) {
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
		
	 // Récupération de l'article
	$em = $this->getDoctrine()->getManager();
	
    // Permet de chercher les articles données via le repository
	$restoFound = $em->getRepository(resto::class)->findAll();

        // la vue
    	return $this->render('manger/afficher.html.twig', [
			'resto'		=> $restoFound,
			'note'		=> $note ?? null,
    	]);
    	
	}

	/**
     * @Route("/manger{id}", name="avis_resto")
     */
    public function avisResto($id)
    {

    	// Récupération de l'article
    	$em = $this->getDoctrine()->getManager();
    	// Permet de chercher l'article donnée en id via le repository
		$restoFound = $em->getRepository(Resto::class)->findById($id);

		// Je place mes erreurs dans un tableau
        $errors = [];
		
        $success = '';
		
		$comments = $em->getRepository(Comments::class)->findBy(['target' => $id]);

        // Si mes inputs sont remplies
        if (!empty($_POST)) {

            // je nettoie les données reçues
            $safe = array_map('trim', array_map('strip_tags', $_POST));
            
            // Je pose mes conditions de validation du formulaire
            if (!empty($safe['comment'])) {
                if (strlen($safe['comment']) <= 50) {
                    $errors[] = 'Votre avis doit comporter au moins 50 caractères';
                }
            } else $errors[] = 'Vous n\'avez pas remplis le champ commentaire';


            if (count($errors) == 0) {

                $errors = array_filter($errors);

                $em = $this->getDoctrine()->getManager();

				$commentData = new Comments();
				
				$session = new Session();

                $commentData->setAuthor($session->get('pseudo'))
                        ->setContent($safe['comment'])
                        ->setTarget($id)
                        ->setDate(new \Datetime);

                //Préparation de la requete.
                $em->persist($commentData);
                //éxecution
                $em->flush();
                
				// Redirection
				
				$success = 'Votre avis a bien été pris en compte !';
            
            } // Fin de 'if (count($errors) == 0)'

        } // Fin de 'if (!empty($_POST))'


    	// la vue
        return $this->render('manger/avis.html.twig', [
			'errors'		=> $errors,
			'success'		=> $success,
			'resto'		 	=> $restoFound,
			'commentaires' 	=> $comments,
        ]);

    }
	
}
