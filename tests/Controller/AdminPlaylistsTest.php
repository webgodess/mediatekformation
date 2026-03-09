<?php

namespace App\Tests\Controller;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class AdminPlaylistsTest extends WebTestCase
{

    private const PLAYLIST_HOMEPAGE = '/admin/playlists';
    private const PHP_PLAYLIST = 'Playlist PHP';
    private function getAdmin()
    {
        $client = static::createClient();
        $adminUser = static::getContainer()
            ->get(UserRepository::class)
            ->findOneBy(['username' => 'admin']);
        $client->loginUser($adminUser);
        return $client;
    }

    public function testAdminPlaylists(): void
    {
        $client = $this->getAdmin();
        $client->request('GET', self::PLAYLIST_HOMEPAGE);
        $this->assertResponseIsSuccessful();
    }

    public function testAdminPlaylistSort(): void
    {
        $client = $this->getAdmin();
        $client->request('GET', '/admin/playlists/tri/name/ASC');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'Playlist Dev Web');
    }

    public function testAdminPlaylistFiltre(): void
    {
        $client = $this->getAdmin();
        $client->request('POST', '/admin/playlists/recherche/name', [
            'recherche' => self::PHP_PLAYLIST,
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $client->getCrawler()->filter('h5'));
        $this->assertSelectorTextContains('h5', text: self::PHP_PLAYLIST);
    }


    public function testAdminPlaylistShowOne(): void
    {
        $client = $this->getAdmin();
        $playlistRepository = static::getContainer()->get(PlaylistRepository::class);
        $playlist = $playlistRepository->findOneBy(['name' => self::PHP_PLAYLIST]);
        $playlistId = $playlist->getId();
        $client->request('GET', "/admin/playlists/playlist/$playlistId");
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', text: self::PHP_PLAYLIST);
    }


    public function testAdminPlaylistCategorie(): void
    {
        $client = $this->getAdmin();
        $categorieRepository = static::getContainer()
            ->get(CategorieRepository::class);
        $categorie = $categorieRepository->findOneBy(['name' => 'DevOps']);
        $categorieId = $categorie->getId();

        $client->request(
            'POST',
            '/admin/playlists/recherche/id/categories',
            ['recherche' => $categorieId]
        );
        $this->assertResponseIsSuccessful();
        $this->assertCount(3, $client->getCrawler()->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Playlist Dev Web');
    }

    public function testAdminPlaylistAdd(): void
    {
        $client = $this->getAdmin();
        $crawler = $client->request('GET', self::PLAYLIST_HOMEPAGE);
        $form = $crawler->filter('.btn-success')->form();
        $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('admin.playlists.add');
    }

    public function testAdminPlaylistEdit(): void
    {
        $client = $this->getAdmin();
        $crawler = $client->request('GET', self::PLAYLIST_HOMEPAGE);
        $form = $crawler->filter('.btn-warning')->first()->form();
        $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('admin.playlists.edit');
    }


    public function testAdminPlaylistRemove(): void
    {
        $client = $this->getAdmin();
        $crawler = $client->request('GET', self::PLAYLIST_HOMEPAGE);
        $form = $crawler->filter('.btn-danger')->first()->form();
        $client->submit($form);
        $this->assertResponseRedirects(self::PLAYLIST_HOMEPAGE);
    }






}
