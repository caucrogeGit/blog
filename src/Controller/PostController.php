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
        // Crée une nouvelle instance de SearchData pour stocker les données de recherche
        $searchData = new SearchData();
        
        // Crée le formulaire de recherche et le lie aux données de recherche
        $form = $this->createForm(SearchDataType::class, $searchData);
        $form->handleRequest($request);

        /** Si le formulaire est soumis et valide, effectue la recherche **/

        if ($form->isSubmitted() && $form->isValid()) {

            // Recherche les articles correspondant aux critères de recherche
            $posts = $postRepository->findBySearch($searchData);

            // Récupérer le nombre de réactions (APPROUVE OU REJETE) par post
            $nbReactionsByPost = $reactionRepository->findNbReactionsByPost($posts);

            // Vérifier si l'utilisateur est connecté avant de récupérer les réactions
            $postReactions = [];
            if ($this->getUser()) {
                $postReactions = $postRepository->findPostIdWithReaction($posts, $this->getUser());
            }

            // Rend la vue avec les articles trouvés et le formulaire de recherche
            return $this->render('pages/post/index.html.twig', [
                'posts' => $posts,
                'postReactions' => $postReactions,
                'nbReactionsByPost' => $nbReactionsByPost,
                'form' => $form->createView(),
            ]);
        }

        /** Si le formulaire n'est pas soumis ou n'est pas valide, affiche tous les articles publiés avec pagination **/

        // Récupérer les post publiés avec pagination
        $posts = $postRepository->findPublishedWithPaginator();

        // Récupérer le nombre de réactions (APPROUVE OU REJETE) par post
        $nbReactionsByPost = $reactionRepository->findNbReactionsByPost($posts);

        // Recupère les réactions des posts de l'utilisateur connecté
        $postReactions = [];
        if ($this->getUser()) {
            $postReactions = $postRepository->findPostIdWithReaction($posts, $this->getUser());
        }

        return $this->render('pages/post/index.html.twig', [
            'posts' => $postRepository->findPublishedWithPaginator(),
            'postReactions' => $postReactions,
            'nbReactionsByPost' => $nbReactionsByPost,
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
