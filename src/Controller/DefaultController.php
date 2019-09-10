<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default/controler", name="default_controler")
     */
    public function index()
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function connect()
    {
        return $this->render('connexion.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function inscription_choix_profil()
    {
        return $this->render('inscription-choix.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
