<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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



    	if (!empty($_POST)){
    		
    	}
    	
    




















































































        return $this->render('manger/ajouter.html.twig', [
            'controller_name' => 'MangerController',
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
