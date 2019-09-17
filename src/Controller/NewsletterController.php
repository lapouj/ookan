<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function index()
    {
        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }
}
