<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post\Post;
use App\Entity\Post\Category;
use App\Repository\Post\PostRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
        private PostRepository $postRepository)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $entityManager->getRepository(Post::class);
    }

    public function load(ObjectManager $manager)
    {
        // Utilisation de Faker pour générer des données fictives en français
        $faker = Factory::create('fr_FR');

        // Création de 10 catégories fictives
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            // Pour éviter les doublons, on concatène deux mots aléatoires
            $category->setName($faker->words(1, true) . ' ' . $faker->words(1, true));
            // Description aléatoire ou null
            $category->setDescription(
                mt_rand(0, 1) == 1 ? $faker->realText(254) : null
            );
            // Ajout de la catégorie au tableau $categories
            $categories[] = $category;

            // Persistance de la catégorie
            $manager->persist($category);
        }

        // Récupération de tous les posts
        $posts = $this->postRepository->findAll();

        // Assignation de 1 à 5 catégories aléatoires à chaque post
        foreach ($posts as $post) {
            for ($i = 0; $i < mt_rand(1, 5); $i++) {
                $post->addCategory(
                    $categories[mt_rand(0, count($categories) - 1)]
                );
            }
        }

        // Sauvegarde des changements dans la base de données
        $manager->flush();
    }

    // Dépendances de cette fixture
    public function getDependencies(): array
    {
        return [PostFixtures::class];
    }
}
