<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\enum\EtatEnum;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class PostFixtures extends Fixture implements FixtureGroupInterface
{
    private $entityManager;
    private $userRepository;
    private $categoryRepository;
    private $tagRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function load(ObjectManager $manager): void
    {
        // Vider la table "Post"
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('post', true));

        // Crée une instance de Faker avec la locale française
        $faker = Factory::create('fr_FR');

        // Récupère les utilisateurs, catégories et tags existants
        $users = $this->userRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $tags = $this->tagRepository->findAll();

        // Vérification des dépendances
        if (empty($users) || empty($categories) || empty($tags)) {
            throw new \LogicException('Veuillez vous assurer que les fixtures User, Category et Tag sont chargées avant Post.');
        }

        // Crée 50 posts fictifs
        for ($i = 0; $i < 50; $i++) {
            $post = new Post();
            $post->setLabel("p-" . $faker->words(4, true));
            $post->setContent($faker->realText(1800));
            $post->setEtat(mt_rand(0, 1) ? EtatEnum::BROUILLON : EtatEnum::PUBLIE);

            // Associer un utilisateur aléatoire
            $post->setUser($faker->randomElement($users));

            // Associer une catégorie aléatoire
            $category = $faker->randomElement($categories);
            $post->addCategory($category);

            // Associer entre 1 et 4 tags aléatoires
            $tagCount = mt_rand(1, 4);
            $assignedTags = $faker->randomElements($tags, $tagCount);
            foreach ($assignedTags as $tag) {
                $post->addTag($tag);
            }

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

    // Définir un groupe pour cette fixture (optionnel)
    public static function getGroups(): array
    {
        return ['blog'];
    }
}
