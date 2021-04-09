<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FakerTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\IdGeneratorTrait;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

abstract class UnitTestCase extends TestCase
{
    use MatchesSnapshots;
    use FakerTrait;
    use IdGeneratorTrait;
}
