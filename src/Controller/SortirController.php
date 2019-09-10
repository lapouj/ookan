<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SortirController extends AbstractController
{
    /**
     * @Route("/sortir", name="sortir")
     */
    public function index()
    {
        return $this->render('sortir/index.html.twig', [
            'controller_name' => 'SortirController',
        ]);
    }
}
