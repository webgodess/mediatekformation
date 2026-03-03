<?php

namespace App\Tests\Entity;
use App\Entity\Formation;
use PHPUnit\Framework\TestCase;



class FormationTest extends TestCase
{
    public function testGetPublishedAtString(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2024-01-21"));
        $this->assertEquals("21/01/2024", $formation->getPublishedAtString());
    }

    public function testGetPublishedAtStringNull(): void
    {
        $formation = new Formation();

        $this->assertEquals("", $formation->getPublishedAtString());
    }

}


