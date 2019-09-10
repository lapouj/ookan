<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MangerController extends AbstractController
{
    /**
     * @Route("/manger", name="manger")
     */
    public function index()
    {
        return $this->render('manger/index.html.twig', [
            'controller_name' => 'MangerController',
        ]);
    }
}
