<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class AllPostController extends AbstractController
{
    #[Route('/allpost', name: 'app_all_post')]
    public function index(PostRepository $postRepository, EntityManagerInterface $entityManager ): Response
    {

        $approvedPosts = $postRepository->findApprovedPosts();
     
        
        // $posts = $entityManager->getRepository(Post::class)->findAll();
        
        return $this->render('all_post/index.html.twig', [
            'controller_name' => 'AllPostController',
            // 'posts' => $posts
            'approvedPosts' => $approvedPosts,
          
            // 'posts' => $postRepository->findBy(['isValid' => 'Approved']),
            

            

        ]);
    }
}
