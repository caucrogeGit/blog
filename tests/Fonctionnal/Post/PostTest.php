<?php

namespace App\Tests\Fonctionnal\Post;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Classe de test fonctionnel pour vérifier le bon fonctionnement des pages liées aux posts.
 */
class PostTest extends WebTestCase
{
    /**
     * Teste que la page d'un post fonctionne correctement.
     */
    public function testPagePostWorks()
    {
        // Crée un client HTTP pour simuler les requêtes
        $client = static::createClient();

        // Récupère le service de génération d'URL
        $urlGeneratorInterface = $client->getContainer()->get('router');

        // Récupère le gestionnaire d'entités Doctrine
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        // Récupère le repository pour l'entité Post
        $postRepository = $entityManager->getRepository(Post::class);

        // Récupère un post dans la base de données
        $post = $postRepository->findOneBy([]);

        // Envoie une requête GET à l'URL générée pour afficher un post spécifique
        // L'URL est générée en utilisant le routeur Symfony avec le nom de la route 'post_show'
        // et en passant le slug du post comme paramètre
        $client->request('GET', $urlGeneratorInterface->generate('post.show', ['slug' => $post->getSlug()]));

        // Vérifie que la réponse est réussie et que le code de statut est 200
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifie que le titre de la page contient le titre du post
        $this->assertSelectorExists('h1');
        $this->assertSelectorTextContains('h1', ucfirst($post->getTitle()));
    }

    /**
     * Teste que le bouton "Retourner au blog" fonctionne correctement.
     */
    public function testButtonReturnToBlogWorks()
    {
        // Crée un client HTTP pour simuler les requêtes
        $client = static::createClient();

        // Récupère le service de génération d'URL
        $urlGeneratorInterface = $client->getContainer()->get('router');

        // Récupère le gestionnaire d'entités Doctrine
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        // Récupère le repository pour l'entité Post
        $postRepository = $entityManager->getRepository(Post::class);

        // Récupère le premier post dans la base de données
        $post = $postRepository->findOneBy([]);

        // Envoie une requête GET à l'URL générée pour afficher un post spécifique
        // L'URL est générée en utilisant le routeur Symfony avec le nom de la route 'post_show'
        // et en passant le slug du post comme paramètre
        $client->request('GET', $urlGeneratorInterface->generate('post.show', ['slug' => $post->getSlug()]));

        // Vérifie que la réponse est réussie et que le code de statut est 200
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifie que le lien "Retourner au blog" est présent et fonctionne
        // Sélectionne le lien "Retourner au blog" et récupère son URL
        $link = $client->getCrawler()->selectLink('Retourner au blog')->link()->getUri();

        // Envoie une requête GET à l'URL du lien "Retourner au blog"
        $client->request('GET', $link);

        // Vérifie que la réponse est réussie et que le code de statut est 200
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertRouteSame('post.index');
    }

    /**
     * Teste le partable du lien de post sur Facebook.
     */
    public function testShareOnFacebookWorks()
    {
        // Déclare une variable pour l'URL de partage sur Facebook
        $lienPartageFacebook = "https://www.facebook.com/sharer/sharer.php?u=";

        // Crée un client HTTP pour simuler les requêtes
        $client = static::createClient();

        // Récupère le service de génération d'URL
        $urlGeneratorInterface = $client->getContainer()->get('router');

        // Récupère le gestionnaire d'entités Doctrine
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        // Récupère le repository pour l'entité Post
        $postRepository = $entityManager->getRepository(Post::class);

        // Récupère le premiser post trouvé dans la base de données
        $post = $postRepository->findOneBy([]);

        // L'URL est générée en utilisant le routeur Symfony avec le nom de la route 'post.show' et en passant le slug du post comme paramètre
        $url = $urlGeneratorInterface->generate('post.show', ['slug' => $post->getSlug()]);

        // Envoie une requête GET avec l'URL générée pour afficher un post spécifique
        $client->request('GET', $url);

        // Vérifie que la réponse est réussie et que le code de statut est 200
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        /** Vérifie que le lien de partage est correcte et fonctionne */

            // Récupere la balise html contenant le filtre et récupère son URL
            $htmlBalise = $client->getCrawler()->filter('.share.facebook');
            // Récupère l'URL du lien de partage dans la balise html <a>
            $aHref = $htmlBalise->attr('href');

            // Récupère l'URL du lien de partage dans la balise html <a>
            $aLink = $htmlBalise->link()->getUri();

        // Vérifie que la chaîne $lienPartageFacebook + le slug du post est la meme que celle contenu dans la chaîne $aLink
        $this->assertEquals($aHref, $lienPartageFacebook .'http://localhost' .$url);
    }

    /**
     * Teste le partable du lien de post sur X
     */
    public function testShareOnXWorks()
    {
        // Déclare une variable pour l'URL de partage sur X
        $lienPartageX = "https://twitter.com/intent/tweet?text=";

        // Crée un client HTTP pour simuler les requêtes
        $client = static::createClient();

        // Récupère le service de génération d'URL
        $urlGeneratorInterface = $client->getContainer()->get('router');

        // Récupère le gestionnaire d'entités Doctrine
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        // Récupère le repository pour l'entité Post
        $postRepository = $entityManager->getRepository(Post::class);

        // Récupère le premiser post trouvé dans la base de données
        $post = $postRepository->findOneBy([]);

        // L'URL est générée en utilisant le routeur Symfony avec le nom de la route 'post.show' et en passant le slug du post comme paramètre
        $url = $urlGeneratorInterface->generate('post.show', ['slug' => $post->getSlug()]);

        // Envoie une requête GET avec l'URL générée pour afficher un post spécifique
        $client->request('GET', $url);

        // Vérifie que la réponse est réussie et que le code de statut est 200
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        /** Vérifie que le lien de partage est correcte et fonctionne */

            // Récupere la balise html contenant le filtre et récupère son URL
            $htmlBalise = $client->getCrawler()->filter('.share.x');
            // Récupère l'URL du lien de partage dans la balise html <a>
            $aHref = $htmlBalise->attr('href');

            // Récupère l'URL du lien de partage dans la balise html <a>
            $aLink = $htmlBalise->link()->getUri();

        // Vérifie que la chaîne $lienPartageFacebook + le slug du post est la meme que celle contenu dans la chaîne $aLink
        $this->assertEquals($aHref, $lienPartageX .'http://localhost' .$url);
    }    
}


