<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class ActivitiesController extends AbstractController
{
    /**
     * @Route("/activities", name="activities")
     */
    public function index()
    {
        return $this->render('activities/index.html.twig', [
            'controller_name' => 'ActivitiesController',
        ]);
    }
}
