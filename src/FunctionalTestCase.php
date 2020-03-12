<?php

namespace Comsave\SymfonyTestingBase;

use Comsave\SymfonyTestingBase\Traits\FakerTrait;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class FunctionalTestCase extends KernelTestCase
{
    use MatchesSnapshots;
    use FakerTrait;

    /** @var KernelInterface */
    protected $bootedKernel;

    public function getService(string $className)
    {
        return $this->getContainer()->get($className);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->getKernel()->getContainer();
    }

    public function getKernel(): KernelInterface
    {
        if (!$this->bootedKernel) {
            $this->bootedKernel = static::bootKernel();
        }

        return $this->bootedKernel;
    }
}
