<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{

    public function testfindAllOrderByName()
    {
        self::bootKernel();
        $playlists = self::getContainer()->get(PlaylistRepository::class)->findAllOrderByName("ASC");
        $this->assertCount(4, $playlists);
        $this->assertEquals("Playlist Dev Web", $playlists[0]->getName());
        $this->assertEquals("Playlist DevOps", $playlists[1]->getName());
        $this->assertEquals("Playlist IA", $playlists[2]->getName());
        $this->assertEquals("Playlist PHP", $playlists[3]->getName());
    }

    public function testfindAllOrderByNameDescends()
    {
        self::bootKernel();
        $playlists = self::getContainer()->get(PlaylistRepository::class)->findAllOrderByName("DESC");
        $this->assertCount(4, $playlists);
        $this->assertEquals("Playlist PHP", $playlists[0]->getName());
        $this->assertEquals("Playlist IA", $playlists[1]->getName());
        $this->assertEquals("Playlist DevOps", $playlists[2]->getName());
        $this->assertEquals("Playlist Dev Web", $playlists[3]->getName());

    }

    public function testfindAllOrderByNumberFormations()
    {
        self::bootKernel();
        $playlists = self::getContainer()->get(PlaylistRepository::class)->findAllOrderByNumberFormations("DESC");
        $this->assertCount(4, $playlists);

    }

    public function testfindByContainValueEmpty()
    {
        self::bootKernel();
        $playlists = self::getContainer()->get(PlaylistRepository::class)->findByContainValue("name", "", "");
        $this->assertCount(4, $playlists);
        $this->assertEquals("Playlist Dev Web", $playlists[0]->getName());
        $this->assertEquals("Playlist DevOps", $playlists[1]->getName());
        $this->assertEquals("Playlist IA", $playlists[2]->getName());
        $this->assertEquals("Playlist PHP", $playlists[3]->getName());
    }

    public function testfindByContainValueTableEmpty()
    {
        self::bootKernel();
        $playlists = self::getContainer()->get(PlaylistRepository::class)->findByContainValue("name", "IA", "");
        $this->assertCount(1, $playlists);
        $this->assertEquals("Playlist IA", $playlists[0]->getName());
    }

    public function testfindByContainValue()
    {
        self::bootKernel();
        $playlists = self::getContainer()->get(PlaylistRepository::class)->findByContainValue("name", "Intelligence artificielle", "categories");
        $this->assertCount(4, $playlists);
        $this->assertEquals("Playlist Dev Web", $playlists[0]->getName());

    }

    public function testAdd()
    {
        self::bootKernel();
        $playlist = new Playlist();
        $playlist->setName("TestPlaylist");
        self::getContainer()->get(PlaylistRepository::class)->add($playlist);
        $this->assertNotNull($playlist->getId());
    }

    public function testRemove()
    {
        self::bootKernel();
        $playlist = new Playlist();
        $playlist->setName("TestPlaylist");
        self::getContainer()->get(PlaylistRepository::class)->add($playlist);
        $id = $playlist->getId();
        self::getContainer()->get(PlaylistRepository::class)->remove($playlist);
        $this->assertNull(self::getContainer()->get(PlaylistRepository::class)->find($id));
    }
}
