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
        return $this->render('activites/add_activity.html.twig', [
            'controller_name' => 'ActivitesController',
        ]);
    }

     public function show()
    {
        return $this->render('activites/show_activities.html.twig', [
            'controller_name' => 'ActivitesController',
        ]);
    }
}
