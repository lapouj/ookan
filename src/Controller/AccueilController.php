<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Entity\Newsletter; // Intéraction

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index()
    {
        $errors = [];

        $disp = '';

        $success = '';

        if(!empty($_POST)) {
            
            // je nettoie les données reçues
            $safe = array_map('trim', array_map('strip_tags', $_POST));

            if (!empty($safe['email-newsletter'])) {
                if(!filter_var($safe['email-newsletter'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Votre adresse email n\'est pas valide';
                }
            } else $errors[] = 'Veuillez renseigner votre adresse mail';

            $emailExist = $this->getDoctrine()->getRepository(Newsletter::class)->findBy(['mail' => $safe['email-newsletter']]);
            if(!empty($emailExist)){
              $errors[] = 'Vous êtes déjà inscrit à la Newsletter';
            }

            if (count($errors) == 0) {

                $errors = array_filter($errors);

                $em = $this->getDoctrine()->getManager();

                $userData = new Newsletter();

                $userData->setMail($safe['email-newsletter']);

                //Préparation de la requete.
                $em->persist($userData);

                //éxecution
                $em->flush();
                
                // Succès
                $success = 'Vous vous êtes inscrit à la newsletter';

                $disp = 'd-none';
                // Redirection
                // return $this->redirectToRoute('inscription_reussite');
				// $success = 'Votre inscription est un succès, bienvenue chez Ookan !';
            
            } // Fin de 'if (count($errors) == 0)'

        } // Fin de 'if (!empty($_POST))'

        return $this->render('accueil/index.html.twig', [
            'mes_erreurs'   => $errors ?? null,
            'success'       => $success ?? null,
            'disp_none'        => $disp ?? null
        ]);
    }
}
