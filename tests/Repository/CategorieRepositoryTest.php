<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class CategorieRepositoryTest extends KernelTestCase
{

    public function testfindAllForOnePlaylist()
    {
        self::bootKernel();
        $playlist = self::getContainer()->get(PlaylistRepository::class)->findOneBy(["name" => "Playlist PHP"]);
        $categories = self::getContainer()->get(CategorieRepository::class)->findAllForOnePlaylist($playlist->getId());
        $this->assertCount(2, $categories);
        $this->assertEquals("Développement web", $categories[0]->getName());
        $this->assertEquals("Intelligence artificielle", $categories[1]->getName());

    }

    public function testAdd()
    {
        self::bootKernel();
        $categorie = new Categorie();
        $categorie->setName("TestCategorie");
        self::getContainer()->get(CategorieRepository::class)->add($categorie);
        $this->assertNotNull($categorie->getId());
    }

    public function testRemove()
    {
        self::bootKernel();
        $categorie = new Categorie();
        $categorie->setName("TestCategorie");
        self::getContainer()->get(CategorieRepository::class)->add($categorie);
        $id = $categorie->getId();
        self::getContainer()->get(CategorieRepository::class)->remove($categorie);
        $this->assertNull(self::getContainer()->get(CategorieRepository::class)->find($id));

    }
}

