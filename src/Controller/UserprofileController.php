<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface; //Connexion à la base données

use App\Entity\UserPro; // Intéraction

class UserprofileController extends AbstractController
{
    /**
     * @Route("/userprofile", name="userprofile")
     */
    public function userprofile($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(UserPro::class)->find($id);

        return $this->render('userprofile/user-profile.html.twig', [
            'user' => $user,
        ]);
    }
}
