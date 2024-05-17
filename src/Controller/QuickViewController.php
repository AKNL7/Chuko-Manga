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

        // Retrieve the post information from the database
        $post = $this->managerRegistry->getRepository(Post::class)->find($id);
        $user = $post->getUser();



     
       
     

        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        // Render the template and pass the post data
        return $this->render('quick_view/index.html.twig', [
            'post' => $post,
            'id' =>$id,
            'user' => $user
     
            
     
                 ]);
    }



}
