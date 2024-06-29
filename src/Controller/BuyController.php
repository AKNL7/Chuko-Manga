<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Payment;
use
    Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;

class BuyController extends AbstractController
{
    private $urlGenerator;

    // Constructeur pour initialiser l'UrlGenerator
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    // Route pour gérer l'achat d'un post
    #[Route('profile/buy/{id}', name: 'app_buy')]
    public function index(PostRepository $postRepository, Post $post, int $id, CartService $cartService, EntityManagerInterface $entityManager): Response
    {

        // Charger les dépendances de Stripe
        require_once '../vendor/autoload.php';
        $stripeSecretKeyDev = $_ENV["STRIPE_SECRET_KEY_DEV"];

        // Obtenir l'email de l'utilisateur actuel
        $userEmail = $this->getUser()->getEmail();

        // Obtenir le contenu complet du panier
        $cart = $cartService->getFullCart();

        // Obtenir le total du panier
        $total = $cartService->getTotal();

        // Récupérer le post par son id
        $post = $postRepository->find($id);

        // Définir le prix de livraison
        $deliveryPrice = 4;
        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        // Initialiser Stripe
        \Stripe\Stripe::setApiKey($stripeSecretKeyDev);


        // Créer une nouvelle session de paiement Stripe Checkout
        $checkout_session = \Stripe\Checkout\Session::create([
            'billing_address_collection' => "required",
            'custom_text' => [
                'submit' => [
                    'message' => 'Rappel Conditions Générales.
                    En cliquant sur j`accepte vous accepter les CGV'
                ],
            ],
            'shipping_address_collection' => [
                'allowed_countries' => ['FR'],
            ],
            'consent_collection' => ['terms_of_service' => 'required'],
            'custom_text' => [
                'terms_of_service_acceptance' => [
                    'message' => 'I agree to the [Terms of Service](https://example.com/terms)',
                ],
            ],
            'customer_email' => $userEmail,

            'payment_method_types' => ['card'],
            'line_items' => array_merge(
                // Détails des articles du panier
                array_map(function (array $item) {
                    return [
                        'price_data' => [
                            'currency' => 'EUR',
                            'unit_amount' => $item['product']->getPostPrice() * 100,
                            'product_data' => [
                                'name' => $item['product']->getPostTitle(),
                            ],
                        ],
                        'quantity' => $item['quantity'],
                    ];
                }, $cart),
                [
                    // Frais de livraison
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'unit_amount' => $deliveryPrice * 100, // Montant en centimes
                            'product_data' => [
                                'name' => 'Frais de livraison',
                            ],
                        ],
                        'quantity' => 1,
                    ]
                ]
            ),


            'mode' => 'payment',
            'metadata' => [


                'Post ID' => $post->getId(),
                'Post Title' => $post->getPostTitle(),
                'Post Price' => $post->getPostPrice(),



            ],

            // Passer l'ID ou d'autres arguments requis
            'success_url' => $this->generateUrl('app_success', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL),

            'cancel_url' => $this->generateUrl('app_cancel', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        $paymentIntentId = $checkout_session->payment_intent;

        // Créer une nouvelle entité de paiement
        $payment = new Payment();
        $payment->setPost($post);
        $payment->setUser($this->getUser());
        $payment->setPrice($total);
        $payment->setDeliveryPrice($deliveryPrice);
        $payment->setCreatedAt(new \DateTimeImmutable());


        // Persister l'entité de paiement
        $entityManager->persist($payment);

        // Envoyer l'entité de paiement dans la base de données
        $entityManager->flush();

        // Rediriger vers l'URL de la session de paiement Stripe
        return $this->redirect($checkout_session->url);
    }


    // Route pour gérer le succès de l'achat
    #[Route('/success', name: 'app_success')]

    public function success(int $id, PostRepository $postRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le post par son id
        $post = $postRepository->find($id);
        if ($post) {
            // Supprimer le post si trouvé
            $entityManager->remove($post);
            $entityManager->flush();
        }
        return $this->render('buy/success.html.twig');
    }

    // Route pour gérer l'annulation de l'achat
    #[Route('/cancel', name: 'app_cancel')]

    public function cancel(): Response
    {

        // Ajouter un message flash pour l'annulation
        $this->addFlash('cancel', 'Votre achat à été annulé');

        return $this->render('buy/cancel.html.twig');
    }
}
