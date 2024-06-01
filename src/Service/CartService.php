<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\PostRepository;

class CartService
{
protected $requestStack;
protected $postRepository;

public function __construct(RequestStack $requestStack, PostRepository $postRepository)
{
$this->requestStack = $requestStack;
$this->postRepository = $postRepository;
}

public function add(int $id): void
{
$session = $this->requestStack->getSession();
$cart = $session->get('cart', []);

if (!empty($cart[$id])) {
// Si ce n'est pas vide, on incrémente
$cart[$id]++;
} else {
// Si c'est vide, on attribue la valeur de l'id à 1
$cart[$id] = 1;
}

$session->set('cart', $cart);
}

public function less(int $id): void
{
$session = $this->requestStack->getSession();
$cart = $session->get('cart', []);

if (!empty($cart[$id])) {
$cart[$id]--;
if ($cart[$id] <= 0) { unset($cart[$id]); } } $session->set('cart', $cart);
    }

    public function remove(int $id): void
    {
    $session = $this->requestStack->getSession();
    $cart = $session->get('cart', []);

    if (!empty($cart[$id])) {
    unset($cart[$id]);
    }

    $session->set('cart', $cart);
    }

    public function getFullCart(): array
    {
    $session = $this->requestStack->getSession();
    $cart = $session->get('cart', []);
    $cartWithData = [];

    foreach ($cart as $id => $qty) {
    $product = $this->postRepository->find($id);
    if ($product) {
    $cartWithData[] = [
    'product' => $product,
    'quantity' => $qty,
    ];
    }
    }

    return $cartWithData;
    }

    public function getTotal(): float
    {
    $total = 0;

    foreach ($this->getFullCart() as $item) {
    $total += $item['product']->getPostPrice() * $item['quantity'];
    }

    return $total;
    }
    }