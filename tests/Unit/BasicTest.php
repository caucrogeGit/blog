<?php

namespace App\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class BasicTest
 *
 * Cette classe contient des tests unitaires de base pour vérifier des assertions simples.
 *
 * @package App\Tests\Unit
 */
class BasicTest extends KernelTestCase
{
    /**
     * Teste si l'expression true est évaluée à true.
     *
     * Cette méthode vérifie simplement que l'expression true est évaluée à true,
     * ce qui est un test de base pour s'assurer que le framework de test fonctionne correctement.
     *
     * @return void
     */
    public function testTrueIsTrue(): void
    {
        $this->assertTrue(true);
    }
}
