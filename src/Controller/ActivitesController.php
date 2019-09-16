<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;
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
        
}
