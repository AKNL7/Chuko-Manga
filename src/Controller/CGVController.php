<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CGVController extends AbstractController
{
    #[Route('/cgv', name: 'app_cgv')]
    public function index(): Response
    {
        // rendu du template sur les conditions générale de ventes
        return $this->render('cgv/index.html.twig', [
            
        ]);
    }

    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentions(): Response
    {

            // rendu du template sur les mentions legales et conditions générales d'utilisation 
        return $this->render('cgv/mention.html.twig', [
           
        ]);
    }
}
