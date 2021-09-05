<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use PhpArsenal\SymfonyMongoTestingFoundation\Traits\AssertMatchesJsonObjectSnapshotTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FakerTrait;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

abstract class UnitTestCase extends TestCase
{
    use MatchesSnapshots;
    use AssertMatchesJsonObjectSnapshotTrait;
    use FakerTrait;
}
