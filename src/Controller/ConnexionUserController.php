<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionUserController extends AbstractController
{
    /**
     * @Route("/connexion/user", name="connexion_user")
     */
    public function index()
    {
        return $this->render('connexion_user/index.html.twig', [
            'controller_name' => 'ConnexionUserController',
        ]);
    }
}
