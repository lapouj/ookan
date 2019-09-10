<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/default/controler", name="default_controler")
     */

    public function contact()
    {
        return $this->render('contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}