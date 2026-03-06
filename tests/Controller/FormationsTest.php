<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
class FormationsTest extends WebTestCase
{
    public function testFormations(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations');

        $this->assertResponseIsSuccessful();

    }

    public function testFormationSort()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/ASC');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h5', 'AWS');
    }

    public function testFormationSortDateDesc(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/publishedAt/DESC');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'Azure');
    }

    public function testFormationFiltreTitle()
    {
        $client = static::createClient();
        $client->request('POST', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Docker',
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'Docker');
    }

    public function testFormationFiltrePlaylist()
    {
        $client = static::createClient();
        $client->request('POST', '/formations/recherche/name/playlist', [
            'recherche' => 'Playlist PHP',
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertCount(2, $client->getCrawler()->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Kubernetes');

    }

    public function testFormationCategorie()
    {
        $client = static::createClient();
        $categorieRepository = static::getContainer()->get(CategorieRepository::class);
        $categorie = $categorieRepository->findOneBy(['name' => 'DevOps']);
        $categorieId = $categorie->getId();
        $client->request('POST', '/formations/recherche/id/categories', ['recherche' => $categorieId]);
        $this->assertResponseIsSuccessful();
        $this->assertCount(3, $client->getCrawler()->filter('h5'));
        $this->assertSelectorTextContains('h5', 'AWS');

    }

    public function testFormationShowOne()
    {
        $client = static::createClient();
        $formationRepository = static::getContainer()->get(FormationRepository::class);
        $formation = $formationRepository->findOneBy(['title' => 'Symfony']);
        $formationId = $formation->getId();
        $client->request('GET', "/formations/formation/$formationId");
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Symfony');
    }





}
