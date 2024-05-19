<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BuyController extends AbstractController
{
          private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    #[Route('/buy/{id}', name: 'app_buy')]
    public function index(PostRepository $postRepository, Post $post, int $id): Response
    {


        // Retrieve the post by its id
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        // Initialize Stripe
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY_DEV']);

        // Create a new Stripe Checkout Session
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $post->getPostTitle(),
                    ],
                    'unit_amount' => $post->getPostPrice() * 100, // Stripe expects the amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($checkout_session->url);
    }
}
