<?php
namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use App\enum\EtatEnum;
use App\Entity\Reaction;
use App\Model\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class PostRepository extends ServiceEntityRepository
{
    private $paginator;
    private $requestStack;

    // Constructeur
    public function __construct(
        ManagerRegistry $registry,
        PaginatorInterface $paginator,
        RequestStack $requestStack
    ) {
        // Appel du constructeur parent avec l'entité Post
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }

    // Méthode pour trouver les posts publiés avec pagination
    /**
     * Retourne une pagination des posts publiés.
     *
     * @return SlidingPagination La pagination des posts publiés.
     */
    public function findPublishedWithPaginator(): SlidingPagination
    {
        // Création du QueryBuilder pour sélectionner les posts publiés
        $queryBuilder = $this->createQueryBuilder('post')
                             ->where('post.state LIKE :state')
                             ->setParameter('state', EtatEnum::PUBLIE)
                             ->orderBy('post.createdAt', 'DESC')
                             ->getQuery()
                             ->getResult();

        // Récupération de la requête actuelle
        $request = $this->requestStack->getCurrentRequest();

        // Retourne la pagination des résultats
        return $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            9
        );
    }

    public function findBySearch(SearchData $searchData): SlidingPagination
    {
        // Création du QueryBuilder pour sélectionner les posts publiés
        $queryBuilder = $this   ->createQueryBuilder('post')
                                ->where('post.state LIKE :state')
                                ->setParameter('state', EtatEnum::PUBLIE)
                                ->orderBy('post.createdAt', 'DESC');

        // Ajout de la condition de recherche sur le titre si searchData.search est défini
        if (!empty($searchData->search)) {
            $queryBuilder   ->andWhere('post.title LIKE :search')
                            ->setParameter('search', '%' . $searchData->search . '%');
        }

        // Récupération de la requête actuelle
        $request = $this->requestStack->getCurrentRequest();

        // Retourne la pagination des résultats
        return $this->paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            9
        );
    }

    public function findPostIdWithReaction(array|SlidingPagination $posts, User $user) : array
    {
        // Si $posts est une instance de SlidingPagination, extraire les éléments
        if ($posts instanceof SlidingPagination) {
            $posts = $posts->getItems();
        }

        // Récupère le repository des réactions
        $reactionRepository = $this->getEntityManager()->getRepository(Reaction::class);
        
        // Récupère toutes les réactions pour des posts et l'utilisateur courant
        $reactions = $reactionRepository->findAllByPostsAndUser($posts, $user);

        // Crée un tableau associatif avec l'identifiant du post comme clé et l'avis de la réaction comme valeur
        $postReactions = [];
        foreach ($reactions as $reaction) {
            $postReactions[$reaction->getPost()->getId()] = $reaction->getAvis();
        }

        return $postReactions;
    }
}