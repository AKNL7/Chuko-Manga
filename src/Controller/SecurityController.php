<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

     // Vérifie si l'utilisateur est déjà connecté et le redirige vers une autre page si nécessaire

      // Récupère l'éventuelle erreur de la dernière tentative de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
       // Récupère le dernier nom d'utilisateur (email généralement) saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
           // Cette méthode ne fait rien physiquement car Symfony intercepte la déconnexion
        // via le firewall configuré dans security.yaml. Symfony gère le processus de
        // déconnexion automatiquement.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
