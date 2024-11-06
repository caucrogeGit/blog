<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\enum\EtatEnum;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PostFixtures extends Fixture
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {
        // Vider la table "Post"
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('post', true));

        // Crée une instance de Faker avec la locale française
        $faker = Factory::create('fr_FR');

        // Crée 50 posts fictifs
        for($i = 0; $i < 50; $i++) {
            $post = new Post();
            $post->setTitle("p-".$faker->words(4, true));
            $post->setContent($faker->realText(1800));
            $post->setState(mt_rand(0, 1) ? EtatEnum::BROUILLON : EtatEnum::PUBLIE);
            // $post->setUser();

            $manager->persist($post);
        }

        $manager->flush();
    }

    // Dépendances de cette fixture
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TagFixtures::class,
            CategoryFixtures::class
        ];
    }
}
