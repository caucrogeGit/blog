<?php

namespace App\Controller\blog;

use App\Repository\Post\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController{

    #[Route('/post', name: 'post.index', methods: ['GET'])]
    public function index(PostRepository $postRepository)
    {   
        return $this->render('pages/post/index.html.twig',
        ['posts' => $postRepository->findPublished()]);
    }
}
