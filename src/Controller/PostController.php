<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController{

    #[Route('/post', name: 'post.index', methods: ['GET'])]
    public function index(PostRepository $postRepository)
    {   
        return $this->render('pages/post/index.html.twig',
        ['posts' => $postRepository->findPublishedWithPaginator()]);
    }

    #[Route('/post/{slug}', name: 'post.show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Post $post): Response
    {
        return $this->render('pages/post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
