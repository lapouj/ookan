<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription_choix_profil()
    {
        return $this->render('inscription/inscription-choix.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
