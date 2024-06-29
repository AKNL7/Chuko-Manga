<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{categoryId}/posts', name: 'app_category_posts')]
    public function index($categoryId, PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {

        // / Récupération de l'entité Category correspondant à l'ID fourni
        $category = $categoryRepository->find($categoryId);

        // Vérification si la catégorie existe
        if (!$category) {

            // Lance une exception si la catégorie n'est pas trouvée
            throw $this->createNotFoundException('Category not found');
        }

        // Récupération des articles (Post) de la catégorie, en filtrant seulement ceux qui sont valides
        $posts = $category->getPosts()->filter(
            function ($post) {
                return $post->isValid();
            }
        );
        return $this->render('category/index.html.twig', [
            'category' => $category, // Objet Category à passer au template
            'posts' => $posts, // Liste des annonces (posts) à passer au template
        ]);
    }
}
