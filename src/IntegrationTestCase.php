<?php

namespace PhpArsenal\SymfonyMongoTestingFoundation;

use PhpArsenal\SymfonyMongoTestingFoundation\Exception\TestEnvironmentRequiredException;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\DatabaseTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\FixturesTrait;
use PhpArsenal\SymfonyMongoTestingFoundation\Traits\SalesforceTrait;
use Symfony\Bundle\FrameworkBundle\Client;

abstract class IntegrationTestCase extends FunctionalTestCase
{
    use DatabaseTrait;
    use FixturesTrait;
    use SalesforceTrait;

    /** @var Client */
    protected $client;

    /** @return Client|object */
    public function getClient(): Client
    {
        if (!$this->client) {
            $this->client = $this->getService('test.client');
        }

        return $this->client;
    }

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->dbOnSetUp();
        $this->sfOnSetUp();
    }

    public function tearDown(): void
    {
        $this->dbOnTearDown();
        $this->sfOnTearDown();
    }

    /**
     * @throws TestEnvironmentRequiredException
     */
    protected function mustBeTestEnvironment(): void
    {
        if ('test' !== $this->getKernel()->getEnvironment()) {
            throw new TestEnvironmentRequiredException();
        }
    }
}
