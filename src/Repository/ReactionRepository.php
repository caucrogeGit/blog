<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Reaction;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

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

    public function findNbReactionsByPost(SlidingPagination $posts): array
    {
        $postIds = array_map(fn($post) => $post->getId(), $posts->getItems());

        $datas = $this->createQueryBuilder('reaction')
            ->select('IDENTITY(reaction.post) AS postId')
            ->addSelect('SUM(CASE WHEN reaction.avis = :like THEN 1 ELSE 0 END) AS nbApprouve')
            ->addSelect('SUM(CASE WHEN reaction.avis = :dislike THEN 1 ELSE 0 END) AS nbRejete')
            ->where('reaction.post IN (:postIds)')
            ->groupBy('reaction.post')
            ->setParameter('like', 'APPROUVE')      // Assurez-vous que 'APPROUVE' correspond à la valeur de l'enum pour les likes
            ->setParameter('dislike', 'REJETE')     // Assurez-vous que 'REJETE' correspond à la valeur de l'enum pour les dislikes
            ->setParameter('postIds', $postIds)
            ->getQuery()
            ->getResult();

        $nbReactionsByPost = [];
        foreach ($datas as $reaction) {
            $nbReactionsByPost[$reaction['postId']] = [
                'nbApprouve' => $reaction['nbApprouve'],
                'nbRejete' => $reaction['nbRejete'],
            ];
        }    

        return $nbReactionsByPost;
    }
}
