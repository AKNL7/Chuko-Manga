<?php

namespace App\Controller;

use App\Entity\Files;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\FilesRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\Forwarding\Request;

class QuickViewController extends AbstractController
{
    private $managerRegistry;
    private $userRepository;

    public function __construct(ManagerRegistry $managerRegistry, UserRepository $userRepository)
    {
        $this->managerRegistry = $managerRegistry;
        $this->userRepository = $userRepository;

    }

    #[Route('/quickview/{id}/', name: 'app_quick_view')]
    public function index(int $id, PostRepository $postRepository, FilesRepository $filesRepository, Post $post, EntityManagerInterface $entityManager,): Response
    {

          // Récupère les informations du post depuis la base de données
        $post = $this->managerRegistry->getRepository(Post::class)->find($id);
        $user = $post->getUser(); // Récupère l'utilisateur associé au post

        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

   // Rendre le template et passer les données du post
        return $this->render('quick_view/index.html.twig', [
            'post' => $post,
            'id' =>$id,
            'user' => $user
     
            
     
                 ]);
    }



}


     
       
     

