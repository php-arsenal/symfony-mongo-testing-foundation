<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FakerTrait;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class FunctionalTestCase extends KernelTestCase
{
    use MatchesSnapshots;
    use FakerTrait;

    public function setUp(): void
    {
        parent::setUp();

        KernelTestCase::bootKernel();
    }

    public function getService(string $serviceName): ?object
    {
        return $this->getContainer()->get($serviceName);
    }
}
