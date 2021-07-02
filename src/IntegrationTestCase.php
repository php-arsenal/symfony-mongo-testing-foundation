<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use PhpArsenal\SymfonyMongoTestingFoundation\Exception\TestEnvironmentRequiredException;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\AssertMatchesJsonObjectSnapshotTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\DatabaseTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FakerTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FixturesTrait;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class IntegrationTestCase extends WebTestCase
{
    use DatabaseTrait;
    use FixturesTrait;
    use FakerTrait;
    use MatchesSnapshots;
    use AssertMatchesJsonObjectSnapshotTrait;

    /** @var ?KernelBrowser */
    protected ?KernelBrowser $client = null;

    public function getClient(): KernelBrowser
    {
        if (!$this->client) {
            $this->client = static::createClient();
        }

        return $this->client;
    }

    public function getService(string $serviceName): ?object
    {
        return $this->getContainer()->get($serviceName);
    }

    public function jsonResponseDecode(Response $response): array
    {
        return json_decode((string)$response->getContent(), true);
    }

    public function setUp(): void
    {
        $this->getClient();
        $this->dbOnSetUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->dbOnTearDown();
    }

    /**
     * @throws TestEnvironmentRequiredException
     */
    protected function mustBeTestEnvironment(): void
    {
        if ('test' !== $this->getContainer()->getParameter('kernel.environment')) {
            throw new TestEnvironmentRequiredException();
        }
    }
}
