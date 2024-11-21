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

            // Génération d'un nom unique pour éviter les collisions
            $uniqueName = $faker->unique()->words(2, true) ?: 'Default Name';
            $category->setLabel($uniqueName);
       
            // Description aléatoire ou null
            $category->setDescription($faker->realText(254));

            // Persistance de la catégorie
            $manager->persist($category);
        }

        // Sauvegarde des données en base
        $manager->flush();
    }
}
