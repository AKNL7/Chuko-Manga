<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SubmitPostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(EntityManagerInterface $entityManager, ): Response
    {

           $form = $this->createForm(SubmitPostType::class);
          


        return $this->render('post/index.html.twig', [
               'form' => $form->createView()
        ]);    
    }
}
