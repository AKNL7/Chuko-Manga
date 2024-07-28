<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    
    #[Route('/profile/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {

        // Récupère les articles avec leurs données complètes et le total du panier
        $cartWithData = $cartService->getFullCart();
        $total = $cartService->getTotal();

        // Rend la vue du panier avec les articles et le total
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'items' => $cartWithData,
            'total' => $total,


        ]);
    }

    // Route pour ajouter un article au panier
    #[Route('/profile/cart/add/{id}', name: 'app_cart_add')]
    public function add(CartService $cartService, Request $request, int $id): Response
    {
        $this->cartService->add($id);
        $newTotal = $this->cartService->getTotalQuantity();

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => true,
                'cartTotal' => $newTotal
            ]);
        }

        return $this->redirectToRoute('app_cart');
    }

    // Route pour supprimer un article du panier
    #[Route('/profile/cart/clear/{id}', name: 'app_cart_clear')]
    public function clearCart(CartService $cartService, int $id): Response
    {
        // Appelle la méthode du service CartService pour vider complètement le panier
        $cartService->clearCart();

        // Redirige vers la page du panier après avoir vidé le panier
        return $this->redirectToRoute('app_cart');
    }
    // Route pour diminuer la quantité d'un article dans le panier
    #[Route('/profile/cart/less/{id}', name: 'app_cart_less')]
    public function less(CartService $cartService, int $id)
    {
        // Diminue la quantité de l'article avec l'ID spécifié dans le panier

        $cartService->less($id);
        return $this->redirectToRoute('app_cart');
    }
}
