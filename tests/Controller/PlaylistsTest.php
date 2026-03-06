<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
class PlaylistsTest extends WebTestCase
{
    public function testPlaylists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');

        $this->assertResponseIsSuccessful();

    }

    public function testPlaylistSort()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h5', 'Playlist Dev Web');
    }

    public function testPlaylistFiltre()
    {
        $client = static::createClient();
        $client->request('POST', '/playlists/recherche/name', [
            'recherche' => 'Playlist PHP',
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $client->getCrawler()->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Playlist PHP');

    }


    public function testNombreFormationSort()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/nombreFormations/ASC');

        $this->assertResponseIsSuccessful();
        $this->assertCount(4, $client->getCrawler()->filter('h5'));
    }


    public function testFormationCategorie()
    {
        $client = static::createClient();
        $categorieRepository = static::getContainer()->get(CategorieRepository::class);
        $categorie = $categorieRepository->findOneBy(['name' => 'DevOps']);
        $categorieId = $categorie->getId();
        $client->request('POST', '/playlists/recherche/id/categories', ['recherche' => $categorieId]);
        $this->assertResponseIsSuccessful();
        $this->assertCount(3, $client->getCrawler()->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Playlist Dev Web');

    }

    public function testPlaylistShowOne(): void
    {
        $client = static::createClient();
        $playlist = static::getContainer()
            ->get(PlaylistRepository::class)
            ->findOneBy(['name' => 'Playlist IA']);
        $client->request('GET', '/playlists/playlist/' . $playlist->getId());
        $this->assertResponseIsSuccessful();
    }



}
