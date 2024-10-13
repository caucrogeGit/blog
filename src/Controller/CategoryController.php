<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category.index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('pages/category/index.html.twig', [
            'categories' => $categories,
        ]);        
    }
    
    #[Route('/category/{slug}', name: 'category.show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Category $category): Response
    {
        return $this->render('pages/category/show.html.twig', [
            'post' => $category,
            'absolutePath' => $this->generateUrl('post.show', ['slug' => $category->getSlug()])
        ]);
    }
}
