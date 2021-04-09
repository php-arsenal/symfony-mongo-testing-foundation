<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FakerTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\IdGeneratorTrait;
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
