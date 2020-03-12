<?php

namespace Comsave\SymfonyTestingBase;

use Comsave\SymfonyTestingBase\Traits\FakerTrait;
use Comsave\SymfonyTestingBase\Traits\IdGeneratorTrait;
use Doctrine\Persistence\ObjectManager;

abstract class Fixture extends \Doctrine\Bundle\FixturesBundle\Fixture
{
    use FakerTrait;
    use IdGeneratorTrait;

    public function save(ObjectManager $manager, $object): void
    {
        $this->setReference(static::class, $object);
        $manager->persist($object);
        $manager->flush();
    }
}
