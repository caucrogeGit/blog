<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TagFixtures extends Fixture
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {
        // Vider la table "tag"
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('tag', true));

        // Crée une instance de Faker avec la locale française
        $faker = Factory::create('fr_FR');

        // Création de 10 tags fictifs
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag();

            // Génération d'un nom de tag unique
            $tag->setLabel("t-" . uniqid() . ' ' . $faker->words(1, true));

            // Description aléatoire ou null
            $tag->setDescription($faker->realText(200));

            // Initialiser createdAt si nécessaire
            $tag->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')));

            // Persistance du tag
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
