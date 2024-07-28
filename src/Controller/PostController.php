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

    // Création d'une nouvelle instance de Post
    $post = new Post();

    // Création du formulaire avec l'entité Post
    $form = $this->createForm(SubmitPostType::class, $post);

    // Gestion de la soumission du formulaire
    $form->handleRequest($request);


    // Vérification si le formulaire est soumis et valid
    if ($form->isSubmitted() && $form->isValid()) {


      // Récupération de l'utilisateur courant
      $user = $this->security->getUser();
      $post->setUser($user);

      // Gestion des fichiers uploadés
      $uploadFiles = $form->get('postImages')->getData();

      if ($uploadFiles) {

        foreach ($uploadFiles as $file) {
          // Génération d'un nom de fichier sécurisé
          $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

          $safeFilename = $slugger->slug($originalFilename);
          $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
          try {
            // Déplacement du fichier vers le répertoire dédié
            $file->move($this->getParameter('post_directory'), $newFilename);
          } catch (FileException $e) {
            // Gestion des erreurs de fichier
          }
          // Création d'une nouvelle entité Files pour chaque fichier
          $newfile = new Files();
          $newfile->setPostImage($newFilename);
          $post->addPostImage($newfile);
          $newfile->setPost($post); // Associe le fichier à l'annonce
          $entityManager->persist($newfile); 
        }

        $post->setIsValid(false);
        $post->setIsSold(false); // Par défaut, l'annonce n'est pas vendue
        $entityManager->persist($post);
        $entityManager->flush();
        


        // Persiste les entités et flush en base de données
        // $entityManager->persist($newfile);
        // $post->setIsValid(false); 
        // $entityManager->persist($post);
        // $entityManager->flush();

        // Marque l'annonce comme vendue (exemple de mise à jour de statut)
        // $post->setIsSold(true); // Met à jour le statut de vendu
        // $entityManager->persist($post);
        // $entityManager->flush();

        // Redirige vers une page de succès après la soumission
        return $this->redirectToRoute('app_post_success');
      }
    }

    return $this->render('post/index.html.twig', [
      'form' => $form->createView(),
      'files' => $post->getPostImages(),  // Récupère les images associées à l'article
    ]);
  }

  #[Route('/profile/post/success', name: 'app_post_success')]
  public function success(): Response
  {
    return $this->render('post/successSubmission.html.twig');
  }
}
