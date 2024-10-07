<?php

namespace App\Repository\Post;

use App\Entity\Post\Post;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

class PostRepository extends ServiceEntityRepository
{
    // Propriétés
    private PaginatorInterface $paginator;
    private RequestStack $requestStack;
    
    // Constructeur
    public function __construct(
        ManagerRegistry $registry,
        PaginatorInterface $paginator,
        RequestStack $requestStack)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }

    // Méthodes
    public function findPublished() : SlidingPagination
    {
        $queryBuilder = $this->createQueryBuilder('post')
                             ->where('post.state Like :state')
                             ->setParameter('state', 'STATE_PUBLISHED')
                             ->orderBy('post.createdAt', 'DESC')
                             ->getQuery()
                             ->getResult();

        $request = $this->requestStack->getCurrentRequest();

        return $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            9
        );
    }
}
