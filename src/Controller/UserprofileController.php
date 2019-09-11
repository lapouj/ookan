<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserprofileController extends AbstractController
{
    /**
     * @Route("/userprofile", name="userprofile")
     */
    public function index()
    {
        return $this->render('userprofile/index.html.twig', [
            'controller_name' => 'UserprofileController',
        ]);
    }
}
