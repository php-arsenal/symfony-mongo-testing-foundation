<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation\Traits;

use PhpArsenal\SymfonyMongoTestingFoundation\SnapshotDriver\SnapshotJsonObjectDriver;
use Spatie\Snapshots\MatchesSnapshots;

trait AssertMatchesJsonObjectSnapshotTrait
{
    use MatchesSnapshots;

    public function assertMatchesJsonObjectSnapshot($actual, array $ignoredProperties = []): void
    {
        $this->assertMatchesSnapshot($actual, new SnapshotJsonObjectDriver($ignoredProperties));
    }
}