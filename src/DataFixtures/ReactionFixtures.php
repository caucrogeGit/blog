<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Reaction;
use App\enum\DecisionEnum;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReactionFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
        private PostRepository $postRepository,
        private UserRepository $userRepository)
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

        // Récupération de tous les utilisateurs
        $users = $this->userRepository->findAll();

        // Pour chaque post
        foreach($posts as $post) {

            // Création de 0 à 9 réactions
            for ($i = 0; $i < mt_rand(0,9); $i++) {

                $reaction = new Reaction();
                
                switch(mt_rand(0,2)){
                    case 0:
                        $reaction->setAvis(DecisionEnum::APPROUVE);
                        break;
                    case 1:
                        $reaction->setAvis(DecisionEnum::REJETE);
                        break;
                    case 2:
                        $reaction->setAvis(DecisionEnum::AUCUNE);
                        break;
                }
    
                switch(mt_rand(0,2)){
                    case 0:
                        $reaction->setModeration(DecisionEnum::APPROUVE);
                        break;
                    case 1:
                        $reaction->setModeration(DecisionEnum::REJETE);
                        break;
                    case 2:
                        $reaction->setModeration(DecisionEnum::AUCUNE);
                        break;
                }
    
                // Récupération d'une adresse IP aléatoire
                $reaction->setIpAddress(mt_rand(0,1) ? $faker->ipv4 : $faker->ipv6);
                $reaction->setIpAddress(mt_rand(0,1) ? $faker->ipv4 : $faker->ipv6);
    
                // Récupération d'un utilisateur aléatoire
                $reaction->setUser($users[mt_rand(0, count($users) - 1)]);
    
                // Ajout de la réaction au post
                $post->addReaction($reaction);

                // Persistance de la réaction
                $manager->persist($reaction);
            }
            
            // Persistance du post
            $manager->persist($post);
        }
       
        $this->entityManager->flush();
	}

    // Dépendances de cette fixture
    public function getDependencies(): array
    {
        return [PostFixtures::class, UserFixtures::class];
    }
}
