<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/profile/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
       
       

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'items' => $cartWithData = $cartService->getFullCart(),
            'total' =>  $total = $cartService->getTotal(),
            
        ]);
    }

    #[Route('/profile/cart/add/{id}', name: 'app_cart_add')]
    public function add(CartService $cartService, int $id)
    {
      $cartService->add($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/profile/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(CartService $cartService, int $id)
    {
        $cartService->remove($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/profile/cart/less/{id}', name: 'app_cart_less')]
    public function less(CartService $cartService, int $id)
    {
        $cartService->less($id);
        return $this->redirectToRoute('app_cart');
    }



}
