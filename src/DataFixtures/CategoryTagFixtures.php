<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Category;
use App\Repository\PostRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class CategoryTagFixtures extends Fixture implements DependentFixtureInterface
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

        // Récupération de tous les posts
        $posts = $this->postRepository->findAll();

        /** Catégories */

        // Création de 10 catégories fictives
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();

            // Pour éviter les doublons, on concatène deux mots aléatoires
            $category->setName("c-".$faker->words(1, true) . ' ' . $faker->words(1, true));

            // Description aléatoire ou null
            $category->setDescription($faker->realText(254));

            // Ajout de la catégorie au tableau $categories
            $categories[] = $category;

            // Persistance de la catégorie
            $manager->persist($category);
        }

        // Assignation de 1 à 5 catégories aléatoires à chaque post
        foreach ($posts as $post) {
            for ($i = 0; $i < mt_rand(1, 5); $i++) {
                $post->addCategory(
                    $categories[mt_rand(0, count($categories) - 1)]
                );
            }
        }

        /** Tags */
        // Création de 10 tags fictifs
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag();

            // Pour éviter les doublons, on concatène deux mots aléatoires
            $tag->setName("t-".$faker->words(1, true) . ' ' . $faker->words(1, true));

            // Description aléatoire ou null
            $tag->setDescription($faker->realText(254));

            // Ajout de la catégorie au tableau $categories
            $tags[] = $tag;

            // Persistance de la catégorie
            $manager->persist($tag);
        }

        // Assignation de 1 à 5 catégories aléatoires à chaque post
        foreach ($posts as $post) {
            for ($i = 0; $i < mt_rand(1, 5); $i++) {
                $post->addTag(
                    $tags[mt_rand(0, count($tags) - 1)]
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
