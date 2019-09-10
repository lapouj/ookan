<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default/controler", name="default_controler")
     */

    public function mentions()
    {
        return $this->render('contact.html.twig', [
            'controller_name' => 'MentionsController',
        ]);
    }
}