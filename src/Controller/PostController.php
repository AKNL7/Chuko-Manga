<?php

namespace App\Controller;

use App\Entity\Files;
use App\Entity\Post;
use App\Form\SubmitPostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger): Response
    {

    // Create a new Post instance
    $post = new Post();

    // Create the form with the Post entity
    $form = $this->createForm(SubmitPostType::class, $post);

    // Handle the form submission
    $form->handleRequest($request);


    // Check if the form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {

     
      $uploadFiles = $form->get('postImages')->getData();
   

      if ($uploadFiles) {

        foreach ($uploadFiles as $file) {
   
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
          $file->move($this->getParameter('post_directory'), $newFilename);
        } catch (FileException $e) {
          // ... handle exception if something happens during file upload
        }
          $newfile = new Files();
          $newfile->setPostImage($file);
          $post->addPostImage($newfile);
        }

        
        
        // foreach ($files as $file) {
          // On génère un nouveau nom de fichier
          // $fichier = md5(uniqid()) . '.' . $file->guessExtension();
          
          // On copie le fichier dans le dossier uploads
          // $file->move(
            //   $this->getParameter('post_directory'),
            //   $fichier
            // );
            
            // On crée l'image dans la base de données
      
  


            $entityManager->persist($newfile);
        $entityManager->persist($post);
      $entityManager->flush();

      return $this->redirectToRoute('post_success');
    }


    
    

        
}

return $this->render('post/index.html.twig', [
  'form'=>$form->createView(),
]);




    }
  }




