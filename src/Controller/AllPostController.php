<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class AllPostController extends AbstractController
{
    #[Route('/allpost', name: 'app_all_post')]
    public function index(PostRepository $postRepository, EntityManagerInterface $entityManager ): Response
    {

   // Récupération de tous les posts disponibles en utilisant le repository 
        $availablePosts = $postRepository->findAvailablePosts();
      
        // Rendu du template Twig avec les posts disponibles
        return $this->render('all_post/index.html.twig', [
            'availablePosts' => $availablePosts,
            
                    ]);
                }
            }
          
           
            

            
