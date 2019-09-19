<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;
use App\Entity\Comments;
use Symfony\Component\HttpFoundation\Session\Session;

class ActivitesController extends AbstractController
{
    /**
     * @Route("/activites", name="activites")
     */
    public function index()
    {
        return $this->render('activites/index.html.twig', [
            'controller_name' => 'ActivitesController',
        ]);
    }

    public function add()
        {

            $errors = [];   

            $success = false;


    if(!empty($_POST)){
            // Nettoyage des données
            $safe = array_map('trim', array_map('strip_tags', $_POST));

            if (strlen($safe['nom'])<3) {
                $errors[] = 'Votre nom d\'activité doit contenir au moins 4 caractères';
            }

            if (strlen($safe['description']) < 50) {
                $errors[] = 'Votre description doit contenir au moins 50 caractères';
            }

            if (!isset($safe['contact'])){
                $errors[] = 'Merci d\'indiquer un moyen de contacter';
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

                $activityData = new Activity(); 
                $activityData   ->setName($safe['nom'])
                ->setDescription($safe['description'])
                ->setContact($safe['contact'])
                ->setStreetname($safe['street_name'])
                ->setStreetnum($safe['street_num'])
                ->setCp($safe['cp'])
                ->setVille($safe['ville']);
                // On prépare la requete.
                $em->persist($activityData);
                // On l'exécute
                $em->flush();

                $success = true;

            }
        }    

        
        return $this->render('activites/add_activity.html.twig', [
            'controller_name' => 'ActivitesController',
            'mes_erreurs' => $errors,
            'success' => $success,
        ]);
    }

    public function show()
    {
    

        // Récupération de l'article
        $em = $this->getDoctrine()->getManager();
        // Permet de chercher les articles données via le repository
        $activitiesFound = $em->getRepository(activity::class)->findAll();

        // la vue
        return $this->render('activites/show_activities.html.twig', 
            ['activities'=> $activitiesFound,
            'controller_name' => 'MangerController',
        ]);
    }  

    /**
     * @Route("/activites{id}", name="avis_activities")
     */
    public function avisActivite($id)
    {

    	// Récupération de l'article
    	$em = $this->getDoctrine()->getManager();
    	// Permet de chercher l'article donnée en id via le repository
		$activityFound = $em->getRepository(Activity::class)->findById($id);

		// Je place mes erreurs dans un tableau
        $errors = [];
		
        $success = '';
		
		$comments = $em->getRepository(Comments::class)->findBy(['target' => $id, 'target_table' => 'activity']);

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
                        ->setTargetTable('activity')
                        ->setDate(new \Datetime());

                //Préparation de la requete.
                $em->persist($commentData);
                //éxecution
                $em->flush();
                
				// Redirection
				
				$success = 'Votre avis a bien été pris en compte !';
            
            } // Fin de 'if (count($errors) == 0)'

        } // Fin de 'if (!empty($_POST))'


    	// la vue
        return $this->render('activites/avis.html.twig', [
			'errors'		=> $errors,
			'success'		=> $success,
			'activities'	=> $activityFound,
			'commentaires' 	=> $comments,
        ]);

    }
        
}
