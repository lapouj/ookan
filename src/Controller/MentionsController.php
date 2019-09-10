<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MentionsController extends AbstractController
{
    /**
     * @Route("/default/controler", name="default_controler")
     */

    public function mentions()
    {
        return $this->render('mentions.html.twig', [
            'controller_name' => 'MentionsController',
        ]);
    }
}