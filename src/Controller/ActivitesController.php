<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
