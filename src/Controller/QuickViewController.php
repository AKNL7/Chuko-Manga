<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuickViewController extends AbstractController
{
    #[Route('/quickview', name: 'app_quick_view')]
    public function index(): Response
    {
        return $this->render('quick_view/index.html.twig', [
            'controller_name' => 'QuickViewController',
        ]);
    }
}
