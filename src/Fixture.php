<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use Doctrine\Persistence\ObjectManager;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FakerTrait;

abstract class Fixture extends \Doctrine\Bundle\FixturesBundle\Fixture
{
    use FakerTrait;

    public function save(ObjectManager $manager, $object): void
    {
        $this->setReference(static::class, $object);
        $manager->persist($object);
        $manager->flush();
    }
}
