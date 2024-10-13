<?php
namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\Pagination\SlidingPagination;
use App\Entity\Post;

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
                             ->setParameter('state', 'STATE_PUBLISHED')
                             ->orderBy('post.createdAt', 'DESC')
                             ->getQuery()
                             ->getResult();

        // Récupération de la requête actuelle
        $request = $this->requestStack->getCurrentRequest();

        // Retourne la pagination des résultats
        return $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), // Numéro de la page actuelle
            9 // Nombre d'éléments par page
        );
    }
}