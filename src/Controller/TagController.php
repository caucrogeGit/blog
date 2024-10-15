<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    #[Route('/tag', name: 'tag.index', methods: ['GET'])]
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('pages/tag/index.html.twig', 
            ['tags' => $tagRepository->findAllWithPaginator(),]);        
    }
    
    #[Route('/tag/{slug}', name: 'tag.show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Tag $tag, Request $request): Response
    {
        // Récupérer l'URL de retour, ou par défaut rediriger vers la page d'accueil
        $returnToUrl = $request->query->get('returnTo', $this->generateUrl('tag.index'));

        return $this->render('pages/tag/show.html.twig', [
            'tag' => $tag,
            'returnToUrl' => $returnToUrl,
        ]);
    }
}
