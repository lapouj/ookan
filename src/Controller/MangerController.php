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




    	echo 'remy suceur';





















































































        return $this->render('manger/ajouter.html.twig', [
            'controller_name' => 'MangerController',
        ]);
    }

    public function show()
    fejfbeefbef
    {




















































































        return $this->render('manger/afficher.html.twig', [
            'controller_name' => 'MangerController',
        ]);
    }
}
