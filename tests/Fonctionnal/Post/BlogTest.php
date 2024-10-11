<?php
namespace App\Tests\Fonctionnal\Post;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BlogTest extends WebTestCase
{
/**
     * Teste que la page d'accueil du blog fonctionne correctement.
     *
     * Ce test vérifie que la page d'accueil des posts est accessible et que le titre
     * de la page contient le texte "Mes articles".
     */
    public function testPostPageWorks(): void
    {
        // Crée un client HTTP pour simuler les requêtes
        $client = static::createClient();

        // Envoie une requête GET à l'URL '/post'
        $client->request('GET', '/post');

        // Vérifie que la réponse est réussie et que le code de statut est 200
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifie qu'un élément <h1> existe sur la page
        $this->assertSelectorExists('h1');

        // Vérifie que le texte de l'élément <h1> contient "Mes articles"
        $this->assertSelectorTextContains('h1', 'Mes articles');
    }

/**
     * Teste que la pagination fonctionne correctement.
     *
     * Ce test vérifie que la pagination des posts fonctionne en vérifiant que la première
     * page contient 9 posts et que la deuxième page contient au moins 1 post.
     */
    public function testPaginationWorks(): void
    {
        // Crée un client HTTP pour simuler les requêtes
        $client = static::createClient();

        // Envoie une requête GET à l'URL '/post'
        $crawler = $client->request('GET', '/post');

        // Vérifie que la réponse est réussie et que le code de statut est 200
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifie qu'il y a 9 éléments avec la classe 'div.card' sur la page
        $posts = $crawler->filter('div.card');
        $this->assertEquals(9, count($posts));

        // Sélectionne le lien de la page 2 et fait une requête GET sur ce lien
        $link = $crawler->selectLink('2')->extract(['href'])[0];
        $crawler = $client->request('GET', $link);

        // Vérifie que la réponse est réussie et que le code de statut est 200
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifie qu'il y a au moins 1 élément avec la classe 'div.card' sur la page 2
        $posts = $crawler->filter('div.card');
        $this->assertGreaterThanOrEqual(1, count($posts));
    }
}