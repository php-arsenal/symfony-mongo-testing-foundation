<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerTrait
{
    public function getService(string $className)
    {
        return $this->getContainer()->get($className);
    }

    public function getContainer(): ContainerInterface
    {
        return self::$container;
    }
}