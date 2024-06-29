<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Payment;
use App\Entity\Post;
use App\Repository\UserRepository;
use App\Repository\PaymentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MySpaceController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/profile/myspace', name: 'app_my_space')]
    public function index(EntityManagerInterface $entityManager, UserRepository $userRepository, PostRepository $postRepository, PaymentRepository $paymentRepository): Response
    {

        // Récupérer les données de l'utilisateur a partir de l'objet $user
        // ucfirst est utilisée pour mettre en majuscule la première lettre de la chaîne de caractères passée en paramètre. 
        $userFirstName = ucfirst($this->getUser()->getFirstName());
        $userLastName = ucfirst($this->getUser()->getLastName());
        $userEmail = ucfirst($this->getUser()->getEmail());
        $userEmailVerifier = $this->getUser()->isVerified();

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les posts soumis par l'utilisateur
        $submittedPosts = $postRepository->findSubmittedPosts($user);

        // Récupérer les posts achetés par l'utilisateur
        $purchasedPosts = $paymentRepository->findPurchasedPosts($user);



        return $this->render('my_space/index.html.twig', [

            'lastName' => $userLastName,
            'firstName' => $userFirstName,
            'email' => $userEmail,
            'verified' => $userEmailVerifier,
            'submittedPosts' => $submittedPosts,
            'purchasedPosts' => $purchasedPosts,


        ]);
    }
}
