<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MasterFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Cette méthode peut rester vide si vous n'avez pas besoin d'initialisation spécifique.
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
