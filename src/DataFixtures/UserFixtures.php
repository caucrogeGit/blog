<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Vider la table "user" pour éviter les conflits
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('user', true));

        // Créer une instance de Faker avec la locale française
        $faker = Factory::create('fr_FR');

        // Création d'un utilisateur administrateur
        $adminUser = new User();
        $adminUser->setEmail('caucroge@gmail.com');
        $adminUser->setFirstName('Roger');
        $adminUser->setLastName('CAUCHON');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setCreationMessage(); // Utilisation de la constante de création
        $adminUser->setPassword(
            $this->passwordHasher->hashPassword($adminUser, 'password123!')
        );

        $manager->persist($adminUser);

        // Création de plusieurs utilisateurs aléatoires
        for ($i = 0; $i < 9; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setRoles(['ROLE_USER']);
            $user->setCreationMessage(); // Utilise le message de création par défaut

            // Génération d'un mot de passe aléatoire répondant aux exigences de sécurité
            $plainPassword = 'User' . $i . '!Pass123';
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $plainPassword)
            );

            $manager->persist($user);
        }

        // Sauvegarde des utilisateurs dans la base de données
        $manager->flush();
    }
}
