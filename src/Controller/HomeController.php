<?php

namespace App\Controller;

// Importation des repositories pour interagir avec la base de données
use App\Repository\FilesRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;

class HomeController extends AbstractController
{



    #[Route('/', name: 'app_home')]
    


    // Elle reçoit en paramètres les repositories nécessaires pour récupérer les données

    public function index(PostRepository $postRepository, FilesRepository $filesRepository, CategoryRepository $categoryRepository): Response
    {

        // Récupération des derniers articles (posts) via le repository des posts
        $latestPosts = $postRepository->findLatestPosts();

        // Récupération des articles (posts) avec les meilleurs prix via le repository des posts  
        $bestPricedPosts = $postRepository->findBestPricedPosts();

        // Récupération de toutes les catégories via le repository des catégories
        $categories = $categoryRepository->findAll();


        // Rendu du template Twig 'home/index.html.twig' avec les données récupérées

        // Les données sont passées au template sous forme de tableau associatif
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'latestPosts' => $latestPosts,
            'bestPricedPosts' => $bestPricedPosts,
            'categories' => $categories,



        ]);
    }
}
