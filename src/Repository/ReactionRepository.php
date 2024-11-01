<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Reaction;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Reaction>
 */
class ReactionRepository extends ServiceEntityRepository
{
    public function __construct(private Security $security, ManagerRegistry $registry,)
    {
        parent::__construct($registry, Reaction::class);
    }

    public function findOneByPostAndUser(Post $post, User $user): Reaction
    {

        return $this->createQueryBuilder('reaction')
            ->Where('reaction.post = :postId')
            ->andWhere('reaction.user = :userUuid')
            ->setParameter('postId', $post->getId())
            ->setParameter('userId', $user->getUuid())
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }

    public function findAllByPostsAndUser(array $posts, User $user): mixed
    {
        $postIds = array_map(fn(Post $post) => $post->getId(), $posts);

        return $this->createQueryBuilder('reaction')
            ->Where('reaction.post IN (:postIds)')
            ->andWhere('reaction.user = :userUuid')
            ->setParameter('postIds', $postIds)
            ->setParameter('userUuid', $user->getUuid())
            ->getQuery()
            ->getResult();
        ;
    }

    
}
