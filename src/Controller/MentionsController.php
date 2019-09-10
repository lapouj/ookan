<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MentionsController extends AbstractController
{
    /**
     * @Route("/mentions", name="mentions")
     */

    public function mentions()
    {
        return $this->render('mentions.html.twig', [
            'controller_name' => 'MentionsController',
        ]);
    }

    
}