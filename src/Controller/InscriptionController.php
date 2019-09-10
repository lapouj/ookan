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
    public function inscription_pro()
    {
        return $this->render('inscription/inscription-pro.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    public function inscription_particulier()
    {
        return $this->render('inscription/inscription-particulier.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    public function inscription_reussite()
    {
        return $this->render('inscription/inscription-reussite.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
