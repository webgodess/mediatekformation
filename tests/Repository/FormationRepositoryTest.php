<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{

    public function testFindAllLasted(): void
    {
        self::bootKernel();
        $formation = self::getContainer()->get(FormationRepository::class)->findAllLasted(2);
        $this->assertCount(2, $formation);
        $this->assertEquals("AWS", $formation[0]->getTitle());
        $this->assertEquals("Azure", $formation[1]->getTitle());


    }


}




