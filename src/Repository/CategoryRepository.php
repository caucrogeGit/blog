<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Category::class);
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }
    
    // Méthodes
    public function findAllWithPaginator() : SlidingPagination
    {
        $queryBuilder = $this->createQueryBuilder('category')
                             ->getQuery()
                             ->getResult();

        $request = $this->requestStack->getCurrentRequest();

        return $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            9);
    }

}
