<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use PhpArsenal\SymfonyMongoTestingFoundation\Traits\ContainerTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FakerTrait;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class FunctionalTestCase extends KernelTestCase
{
    use MatchesSnapshots;
    use FakerTrait;
    use ContainerTrait;

    public function setUp(): void
    {
        parent::setUp();

        KernelTestCase::bootKernel();
    }
}
