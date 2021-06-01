<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FakerTrait;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class FunctionalTestCase extends KernelTestCase
{
    use MatchesSnapshots;
    use FakerTrait;

    public function setUp(): void
    {
        KernelTestCase::bootKernel();
    }

    public function getService(string $className)
    {
        return $this->getContainer()->get($className);
    }
}
