<?php

namespace App\Controller;

use App\Entity\Files;
use App\Entity\Post;
use App\Form\SubmitPostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {

    $post = new Post();
    $form = $this->createForm(SubmitPostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Get uploaded files
      $uploadedFiles = $form->get('postImages')->getData();

      foreach ($uploadedFiles as $uploadedFile) {
        // Create a new Files entity
        $file = new Files();

        // Move the uploaded file to a suitable location
        $fileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move(
          $this->getParameter('files_directory'),
          $fileName
        );

        // Set the file name for the Files entity
        $file->setPostImage($fileName);

        // Associate the file with the post
        $post->addPostImage($file);

        // Persist the Files entity
        $entityManager->persist($file);
      }

      // Persist the Post entity
      $entityManager->persist($post);
      $entityManager->flush();

      // Redirect or do something else after successful form submission
      return $this->redirectToRoute('post_success');
    }

        






        return $this->render('post/index.html.twig', [
          'form'=>$form->createView(),
        ]);
    
}

}