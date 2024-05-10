<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MySpaceController extends AbstractController
{
    #[Route('/profile/myspace', name: 'app_my_space')]
    public function index(): Response
    {

        // Récupérer les données de l'utilisateur 
        $userFirstName = ucfirst($this->getUser()->getFirstName());
        $userLastName = ucfirst($this->getUser()->getLastName());
        $userEmail = ucfirst($this->getUser()->getEmail());
        $userEmailVerifier = $this->getUser()->isVerified();
        


        return $this->render('my_space/index.html.twig', [
            // 'controller_name' => 'MySpaceController',
            'lastName' => $userLastName,
            'firstName' => $userFirstName, 
            'email'=> $userEmail,
            'verified' => $userEmailVerifier

        ]);
    }
}
