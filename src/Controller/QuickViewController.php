<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class QuickViewController extends AbstractController
{
    #[Route('/quickview', name: 'app_quick_view')]
    public function index( PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('quick_view/index.html.twig', [
            'controller_name' => 'QuickViewController',
            'posts' => $posts
        ]);
    }
}
