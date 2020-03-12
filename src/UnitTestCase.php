<?php

namespace Comsave\SymfonyTestingBase;

use Comsave\SymfonyTestingBase\Traits\FakerTrait;
use Comsave\SymfonyTestingBase\Traits\IdGeneratorTrait;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

abstract class UnitTestCase extends TestCase
{
    use MatchesSnapshots;
    use FakerTrait;
    use IdGeneratorTrait;
}
