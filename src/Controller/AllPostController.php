<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AllPostController extends AbstractController
{
    #[Route('/allpost', name: 'app_all_post')]
    public function index(): Response
    {
        return $this->render('all_post/index.html.twig', [
            'controller_name' => 'AllPostController',
        ]);
    }
}
