<?php

namespace App\Tests\Fonctionnal;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BasicTest
 *
 * Cette classe contient des tests fonctionnels pour vérifier que l'environnement de l'application est correctement configuré.
 *
 * @package App\Tests\Fonctionnal
 */
class BasicTest extends WebTestCase
{
    /**
     * Teste si l'environnement est correctement configuré.
     *
     * Cette méthode crée un client HTTP, envoie une requête GET à la racine de l'application
     * et vérifie que la réponse est réussie (code de statut HTTP 200).
     *
     * @return void
     */
    public function testEnvironnementIsOk() :void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/');
        $this->assertResponseIsSuccessful();
    }
}
