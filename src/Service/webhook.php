<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

use App\Entity\Payment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Util\LoggerInterface;

require 'vendor/autoload.php';

// The library needs to be configured with your account's secret key.
// Ensure the key is kept out of any version control system you might be using.
$stripe = new \Stripe\StripeClient('sk_test_51PFFMaRw5z0pDD2WT48lWLEQOMJpzKcXGUcx7ZpGzn1Ux7rNCUlF5mSOjaG3h6x4iegie3Z9o9ABmGhSHkvfIt7f00S90jnqDl');

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_e2f28e7eda8b9779e7f824e73d70a99c9ecf027e05af4c6d52fdc7d37ff98294';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

$kernel = new \App\Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();
$entityManager = $container->get(EntityManagerInterface::class);
$postRepository = $container->get(PostRepository::class);
$userRepository = $container->get(UserRepository::class);
$logger = $container->get(LoggerInterface::class);

$logger->info('Webhook received', ['event' => $event]);

switch ($event->type) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event->data->object;

        $logger->info('Payment intent succeeded', ['payment_intent' => $paymentIntent]);

        $amountReceived = $paymentIntent->amount_received;
        $currency = $paymentIntent->currency;
        $postId = $paymentIntent->metadata->post_id ?? null;
        $userEmail = $paymentIntent->charges->data[0]->billing_details->email;

        $logger->info('Processing payment', [
            'amount_received' => $amountReceived,
            'currency' => $currency,
            'post_id' => $postId,
            'user_email' => $userEmail,
        ]);

        if ($postId && $userEmail) {
            $post = $postRepository->find($postId);
            $user = $userRepository->findOneBy(['email' => $userEmail]);

            $deliveryPrice = 4;
            $price = $amountReceived + $deliveryPrice;
            if ($post && $user) {
                $payment = new Payment();
                $payment->setPost($post);
                $payment->setPrice($post->getPostPrice());
                $payment->setDeliveryPrice($deliveryPrice);
                $payment->setCreatedAt(new \DateTimeImmutable());
                $payment->setUser($user);

                $entityManager->persist($payment);
                $entityManager->flush();

                $logger->info('Payment saved to database', ['payment' => $payment]);
            } else {
                $logger->error('Post or User not found', [
                    'post' => $post,
                    'user' => $user,
                ]);
            }
        } else {
            $logger->error('Post ID or User Email missing', [
                'post_id' => $postId,
                'user_email' => $userEmail,
            ]);
        }
        break;
    case 'payment_intent.payment_failed':
        $paymentIntent = $event->data->object;
        $logger->error('Payment failed', ['error_message' => $paymentIntent->last_payment_error->message]);
        break;
    default:
        $logger->warning('Unknown event type', ['event_type' => $event->type]);
        break;
}




$response->setContent('Success');
$response->setStatusCode(200);
$response->send();
