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

        $category = $categoryRepository->find($categoryId);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $posts = $category->getPosts()->filter(
            function ($post) {
                return $post->isValid();
            }
        );
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'category' => $category,
            'posts' => $posts,
        ]);
    }
}
