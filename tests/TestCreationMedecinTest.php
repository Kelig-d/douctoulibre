<?php

namespace App\Tests;

use App\Entity\Medecin;
use PHPUnit\Framework\TestCase;

class TestCreationMedecinTest extends TestCase
{
    public function testSomething(): void
    {
        $medecin = new Medecin();
        $medecin->setNom("Marc");
        $this->assertSame("Marc", $medecin->getNom());
    }
}
