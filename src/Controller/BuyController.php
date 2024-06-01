<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Service\CartService;

class BuyController extends AbstractController
{
          private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    #[Route('profile/buy/{id}', name: 'app_buy')]
    public function index(PostRepository $postRepository, Post $post, int $id, CartService $cartService): Response
    {

require_once '../vendor/autoload.php';
$stripeSecretKeyDev = $_ENV["STRIPE_SECRET_KEY_DEV"];

$userEmail = $this->getUser()->getEmail();
$cart = $cartService->getFullCart();
$total = $cartService->getTotal();

        // Retrieve the post by its id
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        // Initialize Stripe
        \Stripe\Stripe::setApiKey($stripeSecretKeyDev);
        // $YOUR_DOMAIN = $_ENV["YOUR_DOMAIN"];

        // Create a new Stripe Checkout Session
        $checkout_session = \Stripe\Checkout\Session::create([
            'billing_address_collection'=> "required",
            'custom_text' => [
                'submit' => [
                    'message' => 'Rappel Conditions Générales.
                    En cliquant sur j`accepte vous accepter les CGV'
                ],
            ],
            'consent_collection' => ['terms_of_service' => 'required'],
            'custom_text' => [
                'terms_of_service_acceptance' => [
                    'message' => 'I agree to the [Terms of Service](https://example.com/terms)',
            ],
        ],
            'customer_email' => $userEmail,

            'payment_method_types' => ['card'],
            'line_items' => [
                [
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $post->getPostTitle(),
                    ],
                    'unit_amount' => $post->getPostPrice() * 100, // Stripe expects the amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => [


                    'Post ID' => $post->getId(),
                    'Post Title' => $post->getPostTitle(),
                    'Post Price' => $post->getPostPrice(),  
    
             
              
                ],
            
            // 'success_url' => $this->generateUrl('app_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            // 'cancel_url' => $this->generateUrl('app_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'success_url' => $this->generateUrl('app_success', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL), // Passer l'ID ou d'autres arguments requis
            'cancel_url' => $this->generateUrl('app_cancel', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL), // Passer l'ID ou d'autres arguments requis
        ]);

        return $this->redirect($checkout_session->url);
    }

    #[Route('/success', name: 'app_success')]

    public function success(): Response {

        return $this->render('buy/success.html.twig');
    }

    #[Route('/cancel', name: 'app_cancel')] 

    public function cancel(): Response {
        
        $this->addFlash('cancel', 'Votre achat à été annulé');

        return $this->render('buy/cancel.html.twig');
    }

}
