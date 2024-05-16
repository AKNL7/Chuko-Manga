<?php

namespace App\Controller;

use App\Repository\FilesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(PostRepository $postRepository, FilesRepository $filesRepository): Response
    {
        $latestPosts = $postRepository->findLatestPosts();
        $bestPricedPosts = $postRepository->findBestPricedPosts();
         
        

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'latestPosts' => $latestPosts,
            'bestPricedPosts' => $bestPricedPosts,
            
          
        ]);
    }
}
