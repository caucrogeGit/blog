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
        private PostRepository $postRepository,
        private UserRepository $userRepository)
    {}

    public function load(ObjectManager $manager)
	{
        // Utilisation de Faker pour générer des données fictives en français
        $faker = Factory::create('fr_FR');

        // Récupération de tous les posts et utilisateurs
        $posts = $this->postRepository->findAll();
        $users = $this->userRepository->findAll();

        // Pour chaque post
        foreach($posts as $post) {

            // 75% de chance d'avoir des réactions
            if (mt_rand(1, 4) <= 3) { 

                // Création de 0 à 9 réactions
                for ($i = 0; $i < mt_rand(1,9); $i++) {

                    $reaction = new Reaction();

                    // Récupération d'un avis aléatoire
                    $avisOptions = [DecisionEnum::APPROUVE, DecisionEnum::REJETE];
                    $reaction->setAvis($avisOptions[array_rand($avisOptions)]);

                    // Récupération d'une modération aléatoire
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

            $manager->flush();
        }
	}

    // Dépendances de cette fixture
    public function getDependencies(): array
    {
        return [PostFixtures::class, UserFixtures::class];
    }
}
