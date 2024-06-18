<?php

namespace App\Controller;

use App\Entity\Files;
use App\Entity\Post;
use App\Entity\Category;
use App\Form\SubmitPostType;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
  private $security;

  public function __construct(SecurityBundleSecurity $security)
  {
    $this->security = $security;
  }

  #[Route('/profile/post', name: 'app_post')]
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

      

      $user = $this->security->getUser();
      $post->setUser($user);

      $uploadFiles = $form->get('postImages')->getData();


      if ($uploadFiles) {

        foreach ($uploadFiles as $file) {

          $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

          $safeFilename = $slugger->slug($originalFilename);
          $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
          try {
            $file->move($this->getParameter('post_directory'), $newFilename);
          } catch (FileException $e) {
          }
          $newfile = new Files();
          $newfile->setPostImage($newFilename);
          $post->addPostImage($newfile);
        }




     
        $entityManager->persist($newfile);
        $post->setIsValid(false);
        $entityManager->persist($post);
        $entityManager->flush();

        $post->setIsSold(true); // Met à jour le statut de vendu
        $entityManager->persist($post);
        $entityManager->flush();

        return $this->redirectToRoute('post_success');
      }
    }

    return $this->render('post/index.html.twig', [
      'form' => $form->createView(),
      'files' => $post->getPostImages(),
    ]);
  }




  // #[Route('/category/{categoryId}/posts', name: 'app_category_posts')]
  // public function categoryPosts($categoryId, PostRepository $postRepository, CategoryRepository $categoryRepository): Response
  // {
  //   $category = $categoryRepository->find($categoryId);

  //   if (!$category) {
  //     throw $this->createNotFoundException('Category not found');
  //   }

  //   $posts = $category->getPosts()->filter(function ($post) {
  //     return $post->isValid();
  //   });

  //   return $this->render('category/index.html.twig', [
  //     'category' => $category,
  //     'posts' => $posts,
  //   ]);
  // }
}
