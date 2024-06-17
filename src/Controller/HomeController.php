<?php

namespace App\Controller;

use App\Repository\FilesRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(PostRepository $postRepository, FilesRepository $filesRepository, CategoryRepository $categoryRepository ): Response
    {

        //  $posts = $postRepository->findBy(['isValid' => 'Approved']);
            $latestPosts = $postRepository->findLatestPosts();
            $bestPricedPosts = $postRepository->findBestPricedPosts();
        $categories = $categoryRepository->findAll();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'latestPosts' => $latestPosts,
            'bestPricedPosts' => $bestPricedPosts,
            'categories' => $categories,
            // 'posts' => $posts
            
            
          
        ]);
    }
}
