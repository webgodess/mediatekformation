<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{

    public function testadd(): void
    {
        self::bootKernel();
        $formation = new Formation();
        $formation->setTitle("Formation Test");
        $formation->setDescription("Description de la formation test");
        $formation->setPublishedAt(new \DateTime("2025-12-12"));
        $formation->setVideoId("AZWYX-TEST");
        self::getContainer()->get(FormationRepository::class)->add($formation);
        $this->assertNotNull($formation->getId());
    }

    public function testremove(): void
    {
        self::bootKernel();
        $formation = new Formation();
        $formation->setTitle("Formation Test");
        $formation->setDescription("Description de la formation test");
        $formation->setPublishedAt(new \DateTime("2025-12-12"));
        $formation->setVideoId("AZWYX-TEST");
        self::getContainer()->get(FormationRepository::class)->add($formation);
        $id = $formation->getId();
        self::getContainer()->get(FormationRepository::class)->remove($formation);
        $this->assertNull(self::getContainer()->get(FormationRepository::class)->find($id));
    }

    public function testFindAllLasted(): void
    {
        self::bootKernel();
        $formation = self::getContainer()->get(FormationRepository::class)->findAllLasted(2);
        $this->assertCount(2, $formation);
        $this->assertEquals("AWS", $formation[1]->getTitle());
        $this->assertEquals("Azure", $formation[0]->getTitle());


    }

    public function testfindAllForOnePlaylist(): void
    {
        self::bootKernel();
        $playlist = self::getContainer()->get(PlaylistRepository::class)->findOneBy(["name" => "Playlist IA"]);
        $formations = self::getContainer()->get(FormationRepository::class)->findAllForOnePlaylist($playlist->getId());
        $this->assertCount(3, $formations);
        $this->assertEquals("IA", $formations[0]->getTitle());
        $this->assertEquals("Symfony", $formations[1]->getTitle());
        $this->assertEquals("AWS", $formations[2]->getTitle());



    }

    public function testfindByContainValue(): void
    {
        self::bootKernel();
        $formation = self::getContainer()->get(FormationRepository::class)->findByContainValue("name", "Intelligence artificielle", "categories");
        $this->assertCount(4, $formation);
        $this->assertEquals("Azure", $formation[0]->getTitle());
        $this->assertEquals("Docker", $formation[1]->getTitle());
        $this->assertEquals("PHP", $formation[2]->getTitle());
        $this->assertEquals("IA", $formation[3]->getTitle());

    }

    public function testfindByContainValueNoTable(): void
    {
        self::bootKernel();
        $formation = self::getContainer()->get(FormationRepository::class)->findByContainValue("title", "dev", "");
        $this->assertCount(2, $formation);
        $this->assertEquals("DevOps", $formation[0]->getTitle());
        $this->assertEquals("Dev Web", $formation[1]->getTitle());


    }

    public function testfindByContainValueEmpty(): void
    {
        self::bootKernel();
        $formation = self::getContainer()->get(FormationRepository::class)->findByContainValue("", "", "");
        $this->assertCount(10, $formation);

    }

    public function testfindAllOrderBy(): void
    {
        self::bootKernel();
        $formation = self::getContainer()->get(FormationRepository::class)->findAllOrderBy("publishedAt", "DESC");
        $this->assertCount(10, $formation);
        $this->assertEquals("Azure", $formation[0]->getTitle());
        $this->assertEquals("AWS", $formation[1]->getTitle());
        $this->assertEquals("Kubernetes", $formation[2]->getTitle());
        $this->assertEquals("Docker", $formation[3]->getTitle());
        $this->assertEquals("React", $formation[4]->getTitle());
        $this->assertEquals("Symfony", $formation[5]->getTitle());
        $this->assertEquals("PHP", $formation[6]->getTitle());
        $this->assertEquals("DevOps", $formation[7]->getTitle());
        $this->assertEquals("Dev Web", $formation[8]->getTitle());
        $this->assertEquals("IA", $formation[9]->getTitle());
    }

    public function testfindAllOrderByTable(): void
    {
        self::bootKernel();
        $formation = self::getContainer()->get(FormationRepository::class)->findAllOrderBy("name", "ASC", "categories");
        $this->assertCount(10, $formation);
        $this->assertEquals("Dev Web", $formation[0]->getTitle());

    }


}








