<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ReactionController extends AbstractController
{
    #[Route('/post/reaction/approuve/{id}', name: 'reaction.approuve')]
    public function postApprouve(Post $post): Response
    {
        $user = $this->getUser();

        return new Response('Vous avez approuvé le post');
    }

    #[Route('/post/reaction/rejete/{id}', name: 'reaction.rejete')]
    public function postRejete(Post $post): Response
    {
        return new Response('Vous avez rejeté le post');
    }

    #[Route('/post/reaction/aucune/{id}', name: 'reaction.aucune')]
    public function postAucune(Post $post): Response
    {
        return new Response('Vous avez aucun avis sur le post');
    }
}
