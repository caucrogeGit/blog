<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
        private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Vider la table "Post"
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('user', true));

        // Crée une instance de Faker avec la locale française
        $faker = Factory::create('fr_FR');

        // Crée un utilisateur administrateur
        $user = new User();
        $user->setEmail('caucroge@gmail.com');
        $user->setFirstName('Roger');
        $user->setLastName('Cauchon');
        $user->setPassword($this->passwordHasher->hashPassword($user, '64423151'));
        $user->addRole('ROLE_ADMIN');

        $manager->persist($user);

        // Crée 9 utilisateurs aléatoires
        for($i = 0; $i < 9; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setPassword($this->passwordHasher->hashPassword($user, $faker->password));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
