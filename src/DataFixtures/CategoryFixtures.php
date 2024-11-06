<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {
        // Vider la table "category"
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('category', true));

        // Crée une instance de Faker avec la locale française
        $faker = Factory::create('fr_FR');

        // Création de 10 catégories fictives
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();

            // Pour éviter les doublons, on concatène deux mots aléatoires
            $category->setName($faker->words(1, true) . ' ' . $faker->words(1, true));

            // Description aléatoire ou null
            $category->setDescription($faker->realText(254));

            // Ajout de la catégorie au tableau $categories
            $categories[] = $category;

            // Persistance de la catégorie
            $manager->persist($category);
        }

        $manager->flush();
    }
}
