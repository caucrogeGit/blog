<?php

namespace App\Controller;

use App\Entity\Post;
use App\Model\SearchData;
use App\Form\SearchDataType;
use App\Repository\PostRepository;
use App\Repository\ReactionRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contrôleur pour gérer les articles de blog.
 */
class PostController extends AbstractController
{
    /**
     * Affiche la liste des articles avec pagination et recherche.
     *
     * @param PostRepository $postRepository Le repository des articles.
     * @param Request $request La requête HTTP.
     * @return Response La réponse HTTP avec la vue des articles.
     */
    #[Route('/post', name: 'post.index', methods: ['GET'])]
    public function index(PostRepository $postRepository, 
                        ReactionRepository $reactionRepository, 
                        Request $request): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchDataType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $posts = $postRepository->findBySearch($searchData);
        } else {
            $posts = $postRepository->findPublishedWithPaginator();
        }
       
        // Récupérer les articles de la page courante
        $currentPagePosts = $posts->getItems(); // Récupère les articles paginés de la page courante

        // Récupérer les likes et dislikes pour tous les articles
        $nbLikeDislikeByPost = $reactionRepository->findNbLikeDislikeByPosts($currentPagePosts);
        
        // Identifier les ids des articles de l'utilisateur courant
        $userPostIds = [];
        if ($this->getUser()) {
            
            $userPostIds = $postRepository->findUserPostsOnCurrentPage($currentPagePosts, $this->getUser()); // Récupérer les IDs des posts de l'utilisateur connecté
            $userPostIds = array_column($userPostIds, 'id'); // Simplifier le tableau ex: [0 => ['id' => 1], 1 => ['id' => 2]] => [1, 2]
        }
        
        return $this->render('pages/post/index.html.twig', [
            'posts' => $posts,
            'nbLikeDislikeByPost' => $nbLikeDislikeByPost,
            'userPostIds' => $userPostIds,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{slug}', name: 'post.show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Post $post, Request $request): Response
    {
        // Récupérer l'URL de retour, ou par défaut rediriger vers la page d'accueil
        $returnToUrl = $request->query->get('returnTo', $this->generateUrl('post.index'));
    
        return $this->render('pages/post/show.html.twig', [
            'post' => $post,
            'returnToUrl' => $returnToUrl,
        ]);
    }
}
