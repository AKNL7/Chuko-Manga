<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;

class BuyController extends AbstractController
{
    #[Route('/buy', name: 'app_buy')]
    public function index(PostRepository $postRepository, Post $post ): Response
    {
        $post = new Post();
        $posts = $postRepository->findAll();

require_once '../vendor/autoload.php';

$stripeSecretKeyDev = $_ENV['STRIPE_SECRET_KEY_DEV'];

\Stripe\Stripe::setApiKey($stripeSecretKeyDev);
header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://127.0.0.1:8000/';

$checkout_session = \Stripe\Checkout\Session::create([
  'line_items' => [[
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    'price_data' => [
        'currency' => 'eur',
        'name' => $post->getPostTitle(),
        'unit_amount' => $post->getPostPrice(),
        'post' => $post->getId(),
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/success.html',
  'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

        return $this->render('buy/index.html.twig', [
            'controller_name' => 'BuyController',
        ]);
    }
}
