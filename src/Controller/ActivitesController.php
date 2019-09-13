<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;

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

        $success = '';


if(!empty($_POST)){
            // Nettoyage des données
            $safe = array_map('trim', array_map('strip_tags', $_POST));


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

            }
        }    

        
        return $this->render('activites/add_activity.html.twig', [
            'controller_name' => 'ActivitesController',
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

    
    
}
