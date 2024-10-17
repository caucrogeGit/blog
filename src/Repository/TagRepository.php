<?php
namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\Pagination\SlidingPagination;

/**
 * Classe de repository pour l'entité Tag.
 */
class TagRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;
    private RequestStack $requestStack;

    /**
     * Constructeur du TagRepository.
     *
     * @param ManagerRegistry $registry Le registre des gestionnaires.
     * @param PaginatorInterface $paginator L'instance de PaginatorInterface.
     * @param RequestStack $requestStack L'instance de RequestStack.
     */
    public function __construct(
        ManagerRegistry $registry,
        PaginatorInterface $paginator,
        RequestStack $requestStack
    ) {
        // Appel du constructeur parent avec l'entité Tag
        parent::__construct($registry, Tag::class);
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }
    
    /**
     * Trouve tous les tags avec pagination.
     *
     * @return SlidingPagination La liste paginée des tags.
     */
    public function findAllWithPaginator(): SlidingPagination
    {
        // Crée un query builder pour l'entité Tag
        $queryBuilder = $this->createQueryBuilder('tag')
                             ->getQuery()
                             ->getResult();

        // Récupère la requête actuelle
        $request = $this->requestStack->getCurrentRequest();

        // Paginer les résultats
        return $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            9
        );
    }
}