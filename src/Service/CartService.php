<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\PostRepository;

class CartService

{
    protected $requestStack; // Stack de requêtes Symfony pour accéder à la session
    protected $postRepository; // Repository pour les entités de produits (Post)

    public function __construct(RequestStack $requestStack, PostRepository $postRepository)
    {
        // Injection des dépendances : RequestStack et PostRepository
        $this->requestStack = $requestStack;
        $this->postRepository = $postRepository;
    }

    // Méthode pour ajouter un article au panier
    public function add(int $id): void
    {
        // Récupération de la session Symfony
        $session = $this->requestStack->getSession();

        // Récupération du panier depuis la session ou initialisation à un tableau vide
        $cart = $session->get('cart', []);

        // Vérification de si l'article est déjà présent dans le panier
        if (!empty($cart[$id])) {
            // Si l'article existe, on incrémente sa quantité
            $cart[$id]++;
        } else {
            // Si l'article n'existe pas, on l'ajoute avec une quantité de 1
            $cart[$id] = 1;
        }

        // Sauvegarde du panier mis à jour dans la session
        $session->set('cart', $cart);
    }

    // Méthode pour diminuer la quantité d'un article dans le panier
    public function less(int $id): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        // Vérification si l'article est présent dans le panier
        if (!empty($cart[$id])) {
            // Diminution de la quantité de l'article
            $cart[$id]--;
            if ($cart[$id] <= 0) {
                unset($cart[$id]);
            }
        }
        $session->set('cart', $cart);
    }


    // Méthode pour vider complètement le panier
    public function clearCart(): void
    {
        $session = $this->requestStack->getSession();

        // Remplacement du panier actuel par un tableau vide
        $session->set('cart', []);
    }

    // Méthode pour récupérer le panier avec les détails complets des produits
    public function getFullCart(): array
    {
        $session = $this->requestStack->getSession();

        // Récupération du panier depuis la session ou initialisation à un tableau vide

        $cart = $session->get('cart', []);
        $cartWithData = [];

        // Parcours du panier pour récupérer les détails de chaque produit
        foreach ($cart as $id => $qty) {
            $product = $this->postRepository->find($id);

            // Si le produit est trouvé, ajout dans le tableau $cartWithData avec sa quantité
            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $qty,
                ];
            }
        }

        // Si le produit est trouvé, ajout dans le tableau $cartWithData avec sa quantité
        return $cartWithData;
    }

    // Méthode pour calculer le total du panier
    public function getTotal(): float
    {
        $total = 0;

        // Calcul du total en parcourant tous les produits du panier
        foreach ($this->getFullCart() as $item) {
            $total += $item['product']->getPostPrice() * $item['quantity'];
        }

        // Retourne le total calculé du panier
        return $total;
    }
}
